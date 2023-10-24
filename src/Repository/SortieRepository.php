<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use function PHPUnit\Framework\isNull;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
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

    public function add(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    public function findByFilter(SearchData $searchData): array {

        $res = $this->createQueryBuilder('s')

            ->addSelect('e')
            ->join('s.etat', 'e')
            ->addSelect('o')
            ->join('s.organisateur', 'o')
            ->addSelect('p')
            ->join('s.participants', 'p');

        if(!is_null($searchData->q)){
            $res = $res
                ->andWhere('s.nom LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");

        }

        if(!isNull($searchData->campus)){
            $res = $res
                ->andWhere('s.campus = :campus')
                ->setParameter('campus', $searchData->campus);
        }
        if(!isNull($searchData->dateDebut)){
            $res = $res
                ->andWhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $searchData->dateDebut);
        }
        if(!isNull($searchData->dateFin)){
            $res = $res
                ->andWhere('s.dateHeureDebut <= :dateFin')
                ->setParameter('dateFin', $searchData->dateFin);
        }




       return  $res
            ->getQuery()
            ->getResult();

    }
//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
