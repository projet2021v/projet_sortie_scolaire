<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use function mysql_xdevapi\getSession;

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
    public function findSearch(SearchData $search, UserInterface $user): array
    {
        $dateJour = new \DateTime();

        $query = $this
            ->createQueryBuilder('s')
//            ->select('s')
        ;

        if(!empty($search->mot_cle)) {
            $query = $query
                ->andWhere('s.nom LIKE :mot_cle')
                ->setParameter('mot_cle', "%{$search->mot_cle}%");
        }

        if(!empty($search->orga)) {
            $query = $query
                ->andWhere('s.organisateur = :user')
                ->setParameter('user', $user);
        }

        if(!empty($search->passee)) {
            $query = $query
                ->andWhere('s.date_heure_debut < :date_jour')
                ->setParameter('date_jour', $dateJour);
        }

        if(!empty($search->date_min)) {
            $query = $query
                ->andWhere('s.date_heure_debut >= :date_min')
                ->setParameter('date_min', $search->date_min);
        }

        if(!empty($search->date_max)) {
            $query = $query
                ->andWhere('s.date_heure_debut <= :date_max')
                ->setParameter('date_max', $search->date_max);
        }

        return $query->getQuery()->getResult();
    }
}
