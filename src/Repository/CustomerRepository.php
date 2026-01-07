<?php
// src/Repository/CustomerRepository.php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }

    /**
     * Trouve un client par email
     */
    public function findByEmail(string $email): ?Customer
    {
        return $this->createQueryBuilder('c')
            ->where('c.email = :email')
            ->setParameter('email', $email)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Trouve les clients récents
     */
    public function findRecent(int $limit = 10): array
    {
        return $this->createQueryBuilder('c')
            ->orderBy('c.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche de clients par nom ou email
     */
    public function search(string $query): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.firstName LIKE :query')
            ->orWhere('c.lastName LIKE :query')
            ->orWhere('c.email LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('c.lastName', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte le nombre total de clients
     */
    public function countTotal(): int
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}