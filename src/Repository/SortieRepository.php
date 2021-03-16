<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\Etat;
use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use function mysql_xdevapi\getSession;
use Doctrine\ORM\EntityManagerInterface;


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
     * @param UserInterface $user
     * @param EtatRepository $repo_etat
     * @param EntityManagerInterface $em
     * @return Sortie[]
     */
    public function findSearch(SearchData $search, UserInterface $user, EtatRepository $repo_etat, EntityManagerInterface $em): array
    {
        //on récupère la date du jour
        $dateJour = new \DateTime();

        //on crée une requête
        $query = $this
            ->createQueryBuilder('s')
        ;

        //quand un mot clé a été renseigné
        if(!empty($search->mot_cle)) {
            $query = $query
                ->andWhere('s.nom LIKE :mot_cle')
                ->setParameter('mot_cle', "%{$search->mot_cle}%");
        }

        //quand la case "sorties dont je suis l'organisateur" a été cochée
        if(!empty($search->orga)) {
            $query = $query
                ->andWhere('s.organisateur = :user')
                ->setParameter('user', $user);
        }

        //quand la case "sorties passées" a été cochée
        if(!empty($search->passee)) {
            $query = $query
                ->andWhere('s.date_heure_debut < :date_jour')
                ->setParameter('date_jour', $dateJour);
        }

        //quand la case "date_min (Entre)" a été cochée
        if(!empty($search->date_min)) {
            $query = $query
                ->andWhere('s.date_heure_debut >= :date_min')
                ->setParameter('date_min', $search->date_min);
        }

        //quand la case "date_max ( et )" a été cochée
        if(!empty($search->date_max)) {
            $query = $query
                ->andWhere('s.date_heure_debut <= :date_max')
                ->setParameter('date_max', $search->date_max);
        }

        //quand un site orgnisateur a été sélectionné
        if(!empty($search->site_sortie) and $search->site_sortie != "Tous les sites") {
            $query = $query
                ->andWhere('s.site = :site_id')
                ->setParameter('site_id', $search->site_sortie);
        }

        //récupération des différentes sorties retournées par la requête
        $result_sorties = $query->getQuery()->getResult();

        //récupération des états
        $tab_status = self::getStatus($repo_etat);

        //pour chaque sortie retournée, on vérifie son état et on le met à jour le cas échéant
        foreach($result_sorties as $rs) {
            self::checkState($rs, $repo_etat, $em, $tab_status);
        }

        return $result_sorties;
    }

    /**
     * Récupère les états "clôturée", "en cours" et "passée"
     * Retourne un tableau d'objets Etat
     * [0] - sortie créée
     * [1] - sortie ouverte
     * [2] - sortie clôturée
     * [3] - sortie en cours
     * [4] - sortie passée
     * [5] - sortie annulée
     * @param EtatRepository $repo_etat
     * @return Etat[]
     */
    public function getStatus(EtatRepository $repo_etat)
    {
        return $repo_etat->findAll();
    }

    /**
     * Vérifie l'état d'une sortie et on le met à jour le cas échéant
     * @param Sortie $sortie
     * @param EtatRepository $repo_etat
     * @param EntityManagerInterface $em
     * @param array $tab_status
     */
    public function checkState(Sortie $sortie, EtatRepository $repo_etat, EntityManagerInterface $em, array $tab_status)
    {
        //on récupère la date du jour
        $dateJour = new \DateTime();

        //formatage des dates pour ne pas prendre en compte les heures/min/sec dans la comparaison
        $date_limite = $sortie->getDateLimiteInscription()->format("Y-m-d");
        $date_sortie = $sortie->getDateHeureDebut()->format("Y-m-d");
        $date_jourFormat = $dateJour->format("Y-m-d");

        $changement_a_effectuer = false;

        //si la sortie a été publiée
        if ($sortie->getEtat()->getId() >= 2) {
            //si la date limite d'inscription n'est pas dépassée on passe à l'état "ouverte"
            if ($date_jourFormat < $date_limite and $sortie->getEtat()->getId() != $tab_status[1]->getId()) {
                $sortie->setEtat($tab_status[1]);
                $changement_a_effectuer = true;
            }
            //si la date limite d'inscription est dépassée on passe à l'état "clôturée"
            if ($date_jourFormat > $date_limite and $sortie->getEtat()->getId() != $tab_status[2]->getId()) {
                $sortie->setEtat($tab_status[2]);
                $changement_a_effectuer = true;
            }
            //si la date du jour est celle de la sortie on passe à l'état "en cours"
            if ($date_jourFormat == $date_sortie and $sortie->getEtat()->getId() != $tab_status[3]->getId()) {
                $sortie->setEtat($tab_status[3]);
                $changement_a_effectuer = true;
            }
            //si la date de la sortie est dépassée on passe à l'état "passée"
            if ($date_jourFormat > $date_sortie and $sortie->getEtat()->getId() != $tab_status[4]->getId()) {
                $sortie->setEtat($tab_status[4]);
                $changement_a_effectuer = true;
            }
        }

        //si un changement est nécessaire, on l'effectue en base
        if($changement_a_effectuer) {
            $em->flush();
        }
    }
}
