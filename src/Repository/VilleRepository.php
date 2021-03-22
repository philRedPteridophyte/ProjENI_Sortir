<?php

namespace App\Repository;

use App\Entity\Ville;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ville|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ville|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ville[]    findAll()
 * @method Ville[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ville::class);
    }


    public function findAllThatContains($text): ?Array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.nomVille LIKE :nomVille')
            ->setParameter('nomVille', '%'.$text.'%')
            ->getQuery()
            ->getResult(Query::HYDRATE_ARRAY);
        ;
    }

}
