<?php

namespace App\Repository;

use App\Entity\Archives;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Archives>
 *
 * @method Archives|null find($id, $lockMode = null, $lockVersion = null)
 * @method Archives|null findOneBy(array $criteria, array $orderBy = null)
 * @method Archives[]    findAll()
 * @method Archives[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArchivesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Archives::class);
    }

    public function add(Archives $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Archives $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Archives[] Returns an array of Archives objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Archives
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


    /**
     * Methode qui permet de faire une recherche sur la page archive
    */
    public function search_bac($search, $dateStart, $dateEnd)
    {
        $qb = $this->createQueryBuilder('a');
        $qb->where('a.etat = 1');

        if($search){
            $qb->andWhere('MATCH_AGAINST( a.codejde, a.matiere, a.lot1, a.lot2, a.commentaire) 
                AGAINST(:search boolean)>0')
                ->orWhere('a.localisation = :search')
                ->orWhere('a.numerobac = :search')
                ->addOrderBy('a.date', 'DESC')
            ->setParameter('search', $search);
        }
        if($dateStart){
            if($dateEnd){
                $qb->andWhere('a.date BETWEEN :dateStart AND :dateEnd ')
                ->addOrderBy('a.date', 'DESC')
                ->setParameter('dateStart', $dateStart)
                ->setParameter('dateEnd', $dateEnd);
            }
            else{
                $qb->andWhere('a.date = :dateStart')
                ->setParameter('dateStart', $dateStart);
            }
        }
        return $qb->getQuery()->getResult();
    }

}
