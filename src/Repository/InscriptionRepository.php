<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

    /**
     * @param $idSortie
     * @param $idParticipant
     * @return Inscription[]|null Returns an array of Inscription objects
     * @throws NonUniqueResultException
     */
    public function findBySortieIdAndParticipants($idSortie,$idParticipant): ?Inscription
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.sorties_no_sortie_id = :idSortie')
            ->andWhere('i.participants_no_participant_id = :idParticipant')
            ->setParameter('idSortie', $idSortie)
            ->setParameter('idParticipant', $idParticipant)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Inscription
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
