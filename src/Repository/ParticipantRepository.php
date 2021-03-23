<?php

namespace App\Repository;

use App\Entity\Participant;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Participant|null find($id, $lockMode = null, $lockVersion = null)
 * @method Participant|null findOneBy(array $criteria, array $orderBy = null)
 * @method Participant[]    findAll()
 * @method Participant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Participant::class);
    }

    public function findIfExist($identifiant, $mdp): ?Participant
    {
        return $this->createQueryBuilder('i')
            ->andWhere(
                '(i.pseudo = :id AND i.motDePasse = :mdp) OR
                 (i.mail   = :id AND i.motDePasse = :mdp)
            ')
            ->setParameter('id', $identifiant)
            ->setParameter('mdp', $mdp)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    public function getByIdDetailed(int $id) : ?Participant
    {
        $qb = $this->createQueryBuilder('p')
            ->leftJoin('p.site', 's')
            ->addSelect('s')
            ->where('p.id = :v_user_id')
            ->setParameter('v_user_id', $id);
        //$res = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        //var_dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getResult()[0];
    }


    // /**
    //  * @return Participant[] Returns an array of Participant objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
