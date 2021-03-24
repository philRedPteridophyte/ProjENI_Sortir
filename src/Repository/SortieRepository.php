<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Array_;

/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
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

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function filteredSearch( $lieuxNoLieu , $nom, $datedebut, $datecloture, $suisOrga, $inscr, $pasInscr, $passee, $user) : ?Array
    {
        $qb = $this->createQueryBuilder('s')
            ->join('s.etat', 'e')
            ->join('s.organisateur', 'o')
            ->leftJoin('s.inscription' , 'i')
            ->leftJoin('s.lieu', 'l')
            ->leftJoin('s.inscription' , 'i2', Query\Expr\Join::WITH, 'i2.participant = :v_user_id')
            ->leftJoin('i.participant', 'p')
            ->addSelect('e')
            ->addSelect('o')
            ->addSelect( 'CASE WHEN i2.participant = :v_user_id THEN 1 ELSE 0 END AS user_inscrit')
            ->addSelect("(SELECT count(so.sortie) FROM App\Entity\Inscription so WHERE so.sortie = s.id ) AS participants_count")
            ->where('1 = 1')
            ->setParameter('v_user_id', $user->getId())
            ->having('1 = 1');
        //->addSelect( ' :v_user_id AS HIDDEN user_id')

        //->join('s.lieuxNoLieux','l')
        ;
        if($lieuxNoLieu){
            $qb->andWhere('l.id = :v_lieuxNoLieu');
            $qb->setParameter('v_lieuxNoLieu', $lieuxNoLieu);
        }

        if($nom){
            $qb->andWhere('UPPER(s.nom) LIKE UPPER(:v_nom)');
            $qb->setParameter('v_nom', '%'.$nom.'%');
        }

        if($datedebut && $datecloture){
            $qb->andWhere('(:v_datedebut <= s.datedebut AND :v_datecloture > s.datedebut) 
                OR (:v_datedebut < s.datecloture AND :v_datecloture >= s.datecloture)
                OR (:v_datedebut >= s.datedebut AND :v_datecloture <= s.datecloture)');
            $qb->setParameter('v_datedebut', $datedebut);
            $qb->setParameter('v_datecloture', $datecloture);
        }


        if($suisOrga){
            $qb->andWhere('o.id = :v_user_id');
        }

        if($inscr){
            $qb->andWhere('p.id = :v_user_id');
        }

        if($pasInscr){
            $qb->andWhere(' (CASE WHEN p.id = :v_user_id THEN 0 ELSE 1 END) = 1');
            //$qb->andWhere(':v_user_id NOT IN (SELECT p2.id FROM App\Entity\Sortie s2 JOIN App\Entity\Inscription i2 JOIN App\Entity\Participant p2)');
        }

        if($passee){

        }else{
            $qb->andWhere(":v_date <= DATE_ADD(s.datedebut, ((CASE WHEN s.duree IS NULL THEN 0 ELSE s.duree END) +1 ) , 'minute')");
            $qb->andWhere("e.id != 5");
            $qb->setParameter('v_date', date("Y-m-d H:i:s"));
        }


        //var_dump($qb->getQuery()->getScalarResult());
        //var_dump(substr($qb->getQuery()->getSQL(), 1000, 500));
        //$scal = $qb->getQuery()->getScalarResult();
        $res = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        foreach ($res as $i => $r ){
            if($r[0] == null){
                unset($res[$i]);
            }
            else{
                $res[$i]['sortie'] = $res[$i][0];
                unset($res[$i][0]);
            }
        }
        return $res;
    }

    public function findOneByIdDetailed(int $id) : ?Sortie
    {
        $qb = $this->createQueryBuilder('s')
            ->leftJoin('s.etat', 'e')
            ->leftJoin('s.organisateur', 'o')
            ->leftJoin('s.inscription' , 'i')
            ->leftJoin('i.participant' , 'p')
            ->leftJoin('s.lieu', 'l')
            ->leftJoin('l.ville', 'v')
            ->addSelect('e')
            ->addSelect('o')
            ->addSelect('i')
            ->addSelect('p')
            ->addSelect('l')
            ->addSelect('v')
            ->where('s.id = :v_sortie_id')
            ->setParameter('v_sortie_id', $id);
        //$res = $qb->getQuery()->getResult(Query::HYDRATE_ARRAY);
        //var_dump($qb->getQuery()->getResult());
        return $qb->getQuery()->getResult()[0];
    }
}
