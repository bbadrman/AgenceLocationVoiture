<?php
namespace App\Service;

use App\Entity\Car;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;

class CarAvailabilityService
{
    public function __construct(
        private BookingRepository $bookingRepository,
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * Vérifie si une voiture est disponible pour une période donnée
     */
    public function isCarAvailable(
        Car $car,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate
    ): bool {
        $overlappingBookings = $this->bookingRepository->findOverlappingBookings(
            $car,
            $startDate,
            $endDate
        );

        return count($overlappingBookings) === 0;
    }

    /**
     * Récupère toutes les voitures disponibles selon les critères
     */
    public function getAvailableCars(
        int $cityId,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate,
        array $filters = []
    ): array {
        $qb = $this->entityManager->createQueryBuilder();
        
        $qb->select('c')
            ->from(Car::class, 'c')
            ->where('c.city = :cityId')
            ->andWhere('c.isActive = true')
            ->setParameter('cityId', $cityId);

        // Filtres optionnels
        if (!empty($filters['brand'])) {
            $qb->andWhere('c.brand = :brand')
                ->setParameter('brand', $filters['brand']);
        }

        if (!empty($filters['seats'])) {
            $qb->andWhere('c.seats >= :seats')
                ->setParameter('seats', $filters['seats']);
        }

        if (!empty($filters['fuelType'])) {
            $qb->andWhere('c.fuelType = :fuelType')
                ->setParameter('fuelType', $filters['fuelType']);
        }

        if (!empty($filters['transmission'])) {
            $qb->andWhere('c.transmission = :transmission')
                ->setParameter('transmission', $filters['transmission']);
        }

        if (!empty($filters['minPrice'])) {
            $qb->andWhere('c.pricePerDay >= :minPrice')
                ->setParameter('minPrice', $filters['minPrice']);
        }

        if (!empty($filters['maxPrice'])) {
            $qb->andWhere('c.pricePerDay <= :maxPrice')
                ->setParameter('maxPrice', $filters['maxPrice']);
        }

        $cars = $qb->getQuery()->getResult();

        // Filtrer les voitures non disponibles
        return array_filter($cars, function(Car $car) use ($startDate, $endDate) {
            return $this->isCarAvailable($car, $startDate, $endDate);
        });
    }

    /**
     * Calcule le nombre de jours entre deux dates
     */
    public function calculateNumberOfDays(
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate
    ): int {
        $interval = $startDate->diff($endDate);
        return max(1, $interval->days);
    }

    /**
     * Calcule le prix total
     */
    public function calculateTotalPrice(float $pricePerDay, int $numberOfDays): float
    {
        return $pricePerDay * $numberOfDays;
    }
}