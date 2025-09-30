<?php

namespace App\Repository;

use App\Entity\Controle;
use DateTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Controle>
 *
 * @method Controle|null find($id, $lockMode = null, $lockVersion = null)
 * @method Controle|null findOneBy(array $criteria, array $orderBy = null)
 * @method Controle[]    findAll()
 * @method Controle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ControleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Controle::class);
    }

    public function add(Controle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Controle $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    //    /**
    //     * @return Controle[] Returns an array of Controle objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Controle
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }


    /**
     * Methode qui permet de faire une recherche sur la page archive
    */
    public function search_silo($search, $dateStart, $dateEnd)
    {
        $qb = $this->createQueryBuilder('c');
        $qb->where('c.etatfin = 1');

        if($search){
            $qb->andWhere('MATCH_AGAINST( c.codejde, c.matiere, c.lot) AGAINST(:search boolean)>0')
                ->orWhere('c.silo = :search')
                ->addOrderBy('c.datefin', 'DESC')
            ->setParameter('search', $search);
        }
        if($dateStart){
            if($dateEnd){
                $qb->andWhere('c.date BETWEEN :dateStart AND :dateEnd ')
                ->addOrderBy('c.date', 'DESC')
                ->setParameter('dateStart', $dateStart)
                ->setParameter('dateEnd', $dateEnd);
            }
            else{
                $qb->andWhere('c.date = :dateStart')
                ->setParameter('dateStart', $dateStart);
            }
        }

        $qb->addOrderBy('c.datefin',  'ASC');
        return $qb->getQuery()->getResult();
    }
}