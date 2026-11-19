<?php

namespace App\Repository;

use App\Entity\Recrutement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recrutement>
 *
 * @method Recrutement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recrutement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recrutement[]    findAll()
 * @method Recrutement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecrutementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recrutement::class);
    }
}
