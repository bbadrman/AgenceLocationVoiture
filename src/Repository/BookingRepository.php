<?php
// src/Repository/BookingRepository.php

namespace App\Repository;

use App\Entity\Booking;
use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Booking>
 */
class BookingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Booking::class);
    }

    /**
     * Trouve les réservations qui se chevauchent avec la période donnée
     */
    public function findOverlappingBookings(
        Car $car,
        \DateTimeInterface $startDate,
        \DateTimeInterface $endDate
    ): array {
        return $this->createQueryBuilder('b')
            ->where('b.car = :car')
            ->andWhere('b.status != :cancelledStatus')
            ->andWhere(
                '(b.startDate < :endDate AND b.endDate > :startDate)'
            )
            ->setParameter('car', $car)
            ->setParameter('cancelledStatus', Booking::STATUS_CANCELLED)
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les réservations par client
     */
    public function findByCustomer(int $customerId, int $limit = 10): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.customer = :customerId')
            ->setParameter('customerId', $customerId)
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les réservations récentes
     */
    public function findRecent(int $limit = 20): array
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les réservations par statut
     */
    public function findByStatus(string $status): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.status = :status')
            ->setParameter('status', $status)
            ->orderBy('b.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les réservations à venir
     */
    public function findUpcoming(): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.startDate > :now')
            ->andWhere('b.status IN (:statuses)')
            ->setParameter('now', new \DateTime())
            ->setParameter('statuses', [Booking::STATUS_PENDING, Booking::STATUS_CONFIRMED])
            ->orderBy('b.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les réservations en cours
     */
    public function findCurrent(): array
    {
        $now = new \DateTime();
        
        return $this->createQueryBuilder('b')
            ->where('b.startDate <= :now')
            ->andWhere('b.endDate >= :now')
            ->andWhere('b.status = :status')
            ->setParameter('now', $now)
            ->setParameter('status', Booking::STATUS_CONFIRMED)
            ->orderBy('b.endDate', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Statistiques de réservations
     */
    public function getStats(): array
    {
        $qb = $this->createQueryBuilder('b');
        
        return [
            'total' => (int) $this->createQueryBuilder('b1')
                ->select('COUNT(b1.id)')
                ->getQuery()
                ->getSingleScalarResult(),
            
            'pending' => (int) $this->createQueryBuilder('b2')
                ->select('COUNT(b2.id)')
                ->where('b2.status = :status')
                ->setParameter('status', Booking::STATUS_PENDING)
                ->getQuery()
                ->getSingleScalarResult(),
            
            'confirmed' => (int) $this->createQueryBuilder('b3')
                ->select('COUNT(b3.id)')
                ->where('b3.status = :status')
                ->setParameter('status', Booking::STATUS_CONFIRMED)
                ->getQuery()
                ->getSingleScalarResult(),
            
            'cancelled' => (int) $this->createQueryBuilder('b4')
                ->select('COUNT(b4.id)')
                ->where('b4.status = :status')
                ->setParameter('status', Booking::STATUS_CANCELLED)
                ->getQuery()
                ->getSingleScalarResult(),
            
            'completed' => (int) $this->createQueryBuilder('b5')
                ->select('COUNT(b5.id)')
                ->where('b5.status = :status')
                ->setParameter('status', Booking::STATUS_COMPLETED)
                ->getQuery()
                ->getSingleScalarResult(),
        ];
    }

    /**
     * Calcule le revenu total
     */
    public function getTotalRevenue(): float
    {
        $result = $this->createQueryBuilder('b')
            ->select('SUM(b.totalPrice)')
            ->where('b.status IN (:statuses)')
            ->setParameter('statuses', [Booking::STATUS_CONFIRMED, Booking::STATUS_COMPLETED])
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    /**
     * Trouve les réservations par période
     */
    public function findByDateRange(\DateTimeInterface $startDate, \DateTimeInterface $endDate): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.startDate >= :startDate')
            ->andWhere('b.startDate <= :endDate')
            ->setParameter('startDate', $startDate)
            ->setParameter('endDate', $endDate)
            ->orderBy('b.startDate', 'ASC')
            ->getQuery()
            ->getResult();
    }
}