<?php

namespace App\Repository;

use App\Entity\Inscriptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Inscriptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscriptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscriptions[]    findAll()
 * @method Inscriptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscriptions::class);
    }

    /**
     * @param $idSortie
     * @param $idParticipant
     * @return Inscriptions[]|null Returns an array of Inscriptions objects
     * @throws NonUniqueResultException
     */
    public function findBySortieIdAndParticipants($idSortie,$idParticipant): ?Inscriptions
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
    public function findOneBySomeField($value): ?Inscriptions
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
