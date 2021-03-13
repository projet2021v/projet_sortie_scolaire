<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    /**
     * Récupère les sorties triées suivant une recherche
     * @param SearchData $search
     * @return Sortie[]
     */
    public function findSearch(SearchData $search): array
    {
        $dateJour = new \DateTime();

        $query = $this
            ->createQueryBuilder('s')
            ->select('s')
        ;


        if(!empty($search->mot_cle)) {
            $query = $query
                ->andWhere('s.nom LIKE :mot_cle')
                ->setParameter('mot_cle', "%{$search->mot_cle}%");
        }

        if(!empty($search->passee)) {
            $query = $query
                ->andWhere('s.date_heure_debut < :dj')
                ->setParameter('dj', $dateJour);
        }

        return $query->getQuery()->getResult();
    }
}
