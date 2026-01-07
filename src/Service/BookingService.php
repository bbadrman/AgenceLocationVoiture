<?php

namespace App\Service;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\Customer;
use App\Entity\PickupPoint;
use App\DTO\BookingRequest;
use App\Repository\CarRepository;
use App\Repository\CustomerRepository;
use App\Repository\PickupPointRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingService
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private CarRepository $carRepository,
        private CustomerRepository $customerRepository,
        private PickupPointRepository $pickupPointRepository,
        private CarAvailabilityService $availabilityService,
        private EmailService $emailService
    ) {}

    /**
     * Crée une réservation à partir d'une requête
     */
    public function createBooking(BookingRequest $request): Booking
    {
        // Récupérer la voiture
        $car = $this->carRepository->find($request->getCarId());
        if (!$car) {
            throw new NotFoundHttpException('Voiture non trouvée');
        }

        // Récupérer les points de collecte
        $pickupPoint = $this->pickupPointRepository->find($request->getPickupPointId());
        if (!$pickupPoint) {
            throw new NotFoundHttpException('Point de collecte non trouvé');
        }

        $returnPoint = $request->getReturnPointId()
            ? $this->pickupPointRepository->find($request->getReturnPointId())
            : $pickupPoint;

        // Convertir les dates
        $startDate = new \DateTime($request->getStartDate());
        $endDate = new \DateTime($request->getEndDate());

        // Valider les dates
        if ($startDate >= $endDate) {
            throw new BadRequestHttpException('La date de fin doit être après la date de début');
        }

        if ($startDate < new \DateTime('today')) {
            throw new BadRequestHttpException('La date de début ne peut pas être dans le passé');
        }

        // Vérifier la disponibilité
        if (!$this->availabilityService->isCarAvailable($car, $startDate, $endDate)) {
            throw new BadRequestHttpException('Cette voiture n\'est pas disponible pour cette période');
        }

        // Récupérer ou créer le client
        $customer = $this->customerRepository->findOneBy(['email' => $request->getEmail()]);
        
        if (!$customer) {
            $customer = new Customer();
            $customer->setEmail($request->getEmail());
        }
        
        $customer->setFirstName($request->getFirstName())
            ->setLastName($request->getLastName())
            ->setPhone($request->getPhone());

        // Créer la réservation
        $booking = new Booking();
        $booking->setCar($car)
            ->setCustomer($customer)
            ->setPickupPoint($pickupPoint)
            ->setReturnPoint($returnPoint)
            ->setStartDate($startDate)
            ->setEndDate($endDate)
            ->setNotes($request->getNotes())
            ->setPricePerDay($car->getPricePerDay());

        // Calculer le nombre de jours et le prix total
        $booking->calculateNumberOfDays();
        $booking->calculateTotalPrice();

        // Sauvegarder
        $this->entityManager->persist($customer);
        $this->entityManager->persist($booking);
        $this->entityManager->flush();

        // Envoyer l'email de confirmation
        $this->emailService->sendBookingConfirmation($booking);

        return $booking;
    }

    /**
     * Récupère une réservation par son numéro
     */
    public function getBookingByNumber(string $bookingNumber): ?Booking
    {
        return $this->entityManager->getRepository(Booking::class)
            ->findOneBy(['bookingNumber' => $bookingNumber]);
    }

    /**
     * Annule une réservation
     */
    public function cancelBooking(Booking $booking): void
    {
        $booking->setStatus(Booking::STATUS_CANCELLED);
        $booking->setUpdatedAt(new \DateTimeImmutable());
        
        $this->entityManager->flush();

        // Envoyer l'email d'annulation
        $this->emailService->sendBookingCancellation($booking);
    }
}