<?php

namespace App\Repository;

use App\Entity\Intervention;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Intervention|null find($id, $lockMode = null, $lockVersion = null)
 * @method Intervention|null findOneBy(array $criteria, array $orderBy = null)
 * @method Intervention[]    findAll()
 * @method Intervention[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InterventionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Intervention::class);
    }

    // /**
    //  * @return Intervention[] Returns an array of Intervention objects
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
    public function findOneBySomeField($value): ?Intervention
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * @return Intervention[] de date futur a maintenant
     */
    public function getFuturIntervention(){
        return $this->createQueryBuilder("interv")
            ->where("interv.startdate > :currentTime")
            ->setParameter("currentTime", new \DateTime())
            ->getQuery()
            ->getResult()
            ;
    }

    /**
     * @return Intervention[] de date futur a maintenant
     */
    public function getPastIntervention(){
        return $this->createQueryBuilder("interv")
            ->where("interv.startdate < :currentTime")
            ->setParameter("currentTime", new \DateTime())
            ->getQuery()
            ->getResult()
            ;
    }
    /*public function getThisYearArticles(){
        return $this->createQueryBuilder("a")
            ->where("a.datepubli > '2020-10-10'")
            ->getQuery()
            ->getResult()
            ;
    }*/

    public function getFuturInterventionByUser($employee) {
        return $this->createQueryBuilder("i")
//            ->join("i.employee", "e" )
            ->where("i.employee = :employee")
            ->setParameter("employee",$employee)
            ->andWhere("i.startdate > :currentTime")
            ->setParameter("currentTime", new \DateTime())
            ->getQuery()
            ->getResult();
    }

    public function getPastInterventionByUser($employee) {
        return $this->createQueryBuilder("i")
//            ->join("i.employee", "e" )
            ->where("i.employee = :employee")
            ->setParameter("employee",$employee)
            ->andWhere("i.startdate < :currentTime")
            ->setParameter("currentTime", new \DateTime())
            ->getQuery()
            ->getResult();
    }
}
