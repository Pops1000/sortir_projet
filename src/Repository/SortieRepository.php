<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Participant;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Persistence\ManagerRegistry;

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

        if ($flush)
            $this->getEntityManager()->flush();
    }


    public function findByFilter(SearchData $searchData, ?Participant $user): array
    {

        $res = $this->createQueryBuilder('s')
            ->addSelect('e')
            ->join('s.etat', 'e')
            ->addSelect('o')
            ->join('s.organisateur', 'o')
            ->addSelect('p')
            ->join('s.participants', 'p')

            ->where('s.organisateur = :user AND e.libelle != :libelleArchivee')
            ->orWhere('s.organisateur != :user AND e.libelle != :libelleArchivee AND e.libelle != :libelleCree')

            ->setParameters([
                'user' => $user,
                'libelleCree'=> "Crée",
                'libelleArchivee'=> "Archivée"
        ]);

        if (!is_null($searchData->q)) {
            $res = $res
                ->andWhere('s.nom LIKE :q')
                ->setParameter('q', "%{$searchData->q}%");
        }

        if (!is_null($searchData->campus)) {
            $res = $res
                ->andWhere('s.campus = :campus')
                ->setParameter('campus', $searchData->campus);
        }

        if (!is_null($searchData->dateDebut)) {
            $res = $res
                ->andWhere('s.dateHeureDebut >= :dateDebut')
                ->setParameter('dateDebut', $searchData->dateDebut);
        }

        if (!is_null($searchData->dateFin)) {
            $dateFin = $searchData->dateFin->modify('+1 day');
            $res = $res
                ->andWhere('s.dateHeureDebut <= :dateFin')
                ->setParameter('dateFin', $dateFin);
        }
        if (($searchData->isOrganisateur)) {
            $res = $res
                ->andWhere('s.organisateur = :user')
                ->setParameter('user', $user);
        }
        if (($searchData->isInscrit)) {
            $res = $res
                ->andWhere(':user IN (p)')
                ->setParameter('user', $user);
        }
        if (($searchData->isNotInscrit)) {
            $res = $res
                ->andWhere(':user NOT IN (p)')
                ->setParameter('user', $user);

        }
        if (($searchData->isPassees)) {
            //$date = new \DateTime('now');
            //$date->modify('1 month ago');
            //$res = $res
            //    ->andWhere('s.dateHeureDebut <= :date')
            //    ->setParameter('date', $date);
            $res = $res
                ->andWhere('e.libelle = :libellePasse')
                ->setParameter('libellePasse', "Passée");
        }

        return $res
            ->orderBy('s.dateHeureDebut', 'ASC')
            ->getQuery()
            ->getResult();
    }
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

