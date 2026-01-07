<?php
// src/Repository/PickupPointRepository.php

namespace App\Repository;

use App\Entity\PickupPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PickupPoint>
 */
class PickupPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PickupPoint::class);
    }

    /**
     * Trouve tous les points de collecte actifs
     */
    public function findAllActive(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isActive = true')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les points de collecte par ville
     */
    public function findByCity(int $cityId): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.city = :cityId')
            ->andWhere('p.isActive = true')
            ->setParameter('cityId', $cityId)
            ->orderBy('p.type', 'ASC')
            ->addOrderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les agences par ville
     */
    public function findAgenciesByCity(int $cityId): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.city = :cityId')
            ->andWhere('p.type = :type')
            ->andWhere('p.isActive = true')
            ->setParameter('cityId', $cityId)
            ->setParameter('type', PickupPoint::TYPE_AGENCY)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Trouve les aéroports par ville
     */
    public function findAirportsByCity(int $cityId): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.city = :cityId')
            ->andWhere('p.type = :type')
            ->andWhere('p.isActive = true')
            ->setParameter('cityId', $cityId)
            ->setParameter('type', PickupPoint::TYPE_AIRPORT)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}