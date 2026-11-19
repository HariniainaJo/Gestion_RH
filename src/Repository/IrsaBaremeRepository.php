<?php

namespace App\Repository;

use App\Entity\IrsaBareme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class IrsaBaremeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IrsaBareme::class);
    }

    /**
     * Retourne la tranche applicable pour un salaire donné.
     */
    public function findTrancheForSalaire(float $salaire): ?IrsaBareme
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.salaireMin <= :salaire')
            ->andWhere('b.salaireMax IS NULL OR b.salaireMax >= :salaire')
            ->setParameter('salaire', $salaire)
            ->orderBy('b.salaireMin', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}

