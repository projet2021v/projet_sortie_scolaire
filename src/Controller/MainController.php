<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Site;
use App\Form\SearchType;
use App\Repository\EtatRepository;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class MainController extends AbstractController
{
//    /**
//     * @Route("/", name="main")
//     * @param SortieRepository $repoSortie
//     * @param SiteRepository $repoSite
//     * @return Response
//     */
//    public function main(SortieRepository $repoSortie, SiteRepository $repoSite): Response
//    {
//        $sorties = $repoSortie->findAll();
//        $sites = $repoSite->findAll();
//        $dateJour = new \DateTime();
//
//
//
//        return $this->render('main/index.html.twig', [
//            'sorties' => $sorties,
//            'sites' => $sites,
//            'dateJour' => $dateJour
//        ]);
//    }

    /**
     * @Route("/", name="secu")
     * @return Response
     */
    public function secu(): Response
    {
        if($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('main');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/main", name="main")
     * @param SortieRepository $repoSortie
     * @param SiteRepository $repoSite
     * @param Request $request
     * @param UserInterface $user
     * @return Response
     */
    public function main(SortieRepository $repoSortie, SiteRepository $repoSite, Request $request, UserInterface $user, EtatRepository $repo_etat, EntityManagerInterface $em): Response
    {
        //création d'un objet SearchData
        $data = new SearchData();

        //création d'un formulaire SearchType
        $form = $this->createForm(SearchType::class, $data);

        //récupération de tous les sites en base
        $sites = $repoSite->findAll();

        //création d'un tableau de string contenants les noms de tous les sites
        $sites_associatif = [];
        foreach ($sites as $value) {
            $sites_associatif[$value->getNom()] = $value;
        }

        //ajout au début du précédent tableau d'une première valeur pour pouvoir afficher les sorties de tous les sites
        $t = array("Tous les sites"=>"Tous les sites");
        $sites_associatif = array_merge($t, $sites_associatif);

        //ajout d'un champ select au formulaire, affichant les valeurs du tableau
        $form
            ->add('site_sortie', ChoiceType::class, [
                'label' => 'Site : ',
                'choices' => $sites_associatif
            ]);

        //gestion de la requête de recherche par le formulaire
        $form->handleRequest($request);

        //récupération des sorties en base en fonction des critères de la recherche
        $sorties = $repoSortie->findSearch($data, $user, $repo_etat, $em);

        //récupération de la date du jour
        $date_jour = new \datetime();

        //affichage de la vue
        return $this->render('main/index.html.twig', [
            'sorties' => $sorties,
            'sites' => $sites_associatif,
            'date_jour' => $date_jour,
            'form' => $form->createView()
        ]);
    }
}
