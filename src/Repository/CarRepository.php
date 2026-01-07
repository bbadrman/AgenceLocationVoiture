<?php
// src/Repository/CarRepository.php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    /**
     * Trouve les voitures disponibles par ville
     */
    public function findAvailableByCity(int $cityId): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.city = :cityId')
            ->andWhere('c.isActive = true')
            ->setParameter('cityId', $cityId)
            ->orderBy('c.brand', 'ASC')
            ->addOrderBy('c.model', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère toutes les marques distinctes
     */
    public function findDistinctBrands(): array
    {
        return $this->createQueryBuilder('c')
            ->select('DISTINCT c.brand')
            ->where('c.isActive = true')
            ->orderBy('c.brand', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();
    }

    /**
     * Recherche de voitures avec filtres
     */
    public function searchCars(array $criteria = []): array
    {
        $qb = $this->createQueryBuilder('c')
            ->where('c.isActive = true');

        if (!empty($criteria['cityId'])) {
            $qb->andWhere('c.city = :cityId')
                ->setParameter('cityId', $criteria['cityId']);
        }

        if (!empty($criteria['brand'])) {
            $qb->andWhere('c.brand = :brand')
                ->setParameter('brand', $criteria['brand']);
        }

        if (!empty($criteria['seats'])) {
            $qb->andWhere('c.seats >= :seats')
                ->setParameter('seats', $criteria['seats']);
        }

        if (!empty($criteria['fuelType'])) {
            $qb->andWhere('c.fuelType = :fuelType')
                ->setParameter('fuelType', $criteria['fuelType']);
        }

        if (!empty($criteria['transmission'])) {
            $qb->andWhere('c.transmission = :transmission')
                ->setParameter('transmission', $criteria['transmission']);
        }

        if (isset($criteria['minPrice'])) {
            $qb->andWhere('c.pricePerDay >= :minPrice')
                ->setParameter('minPrice', $criteria['minPrice']);
        }

        if (isset($criteria['maxPrice'])) {
            $qb->andWhere('c.pricePerDay <= :maxPrice')
                ->setParameter('maxPrice', $criteria['maxPrice']);
        }

        $qb->orderBy('c.pricePerDay', 'ASC');

        return $qb->getQuery()->getResult();
    }
}