<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Form\SortieType;
use App\Form\VilleType;
use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="creer_sortie")
     * @param Request $request
     * @return Response
     */
    public function creer_sortie(LieuRepository $lieuRepo, Request $request, UserInterface $user, SortieRepository $sortieRepository): Response
    {
        //Affichage de valeur des villes


//        $tableauDeLieux = [];
//        foreach ($lieux as $lieu) {
//            $tableauDeLieux[] = $lieu;
//        }
//        dump($tableauDeLieux);
        //recupération du site de rattachement de l'utilisateur identifié

//        $idSiteOrganisateur = $user->getSite()->getId();
//        dump($idSiteOrganisateur);
//        $listeLieuxVilleOrganisatrice = $sortieRepository->findAllByIdVilleOrga($idSiteOrganisateur);
//        dump($listeLieuxVilleOrganisatrice);

        $idSiteOrganisateur = $user->getSite()->getId();
        $sortiesEnBD = $sortieRepository->findAll();
        $tableauDeLieux = [];

        foreach ($sortiesEnBD as $sortieEnBD){
            $lieuRattache = new Lieu();
            if($sortieEnBD->getSite()->getId() == $idSiteOrganisateur){
                $lieuRattache = $sortieEnBD->getLieu();
                $tableauDeLieux [] = $lieuRattache;
            }
        }
        dump($tableauDeLieux);



        $session = $request->getSession();

        $sortie = new Sortie();
        //création d'un formulaire sur la base de l'entité Sortie
        $form = $this->createForm(SortieType::class, $sortie);

        //hydratation du formulaire
        $form->handleRequest($request);

        $lieu = new Lieu();
        $form2 = $this->createForm(LieuType::class, $lieu);
        $form2->handleRequest($request);

        $ville = new Ville();
        $form3 = $this->createForm(VilleType::class, $ville);
        $form3->handleRequest($request);

//        $form2
//            ->add('lieu', null, [
//                'label' => ' LISTE DES VILLE: ',
//                'choice_label' => 'nom'
//            ]);

        //si le formulaire a été soumis

        if($form->isSubmitted() && $form->isValid()) {
            dump('après');
            $etat = new Etat();
            $etat->setLibelle("En cour");
            $sortie->s.etEtat($etat);
            $sortie->setOrganisateur($user);
            //création de la sortie en base
            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            var_dump($sortie);
            dump($sortie);

            $em->flush();

            //redirection vers la page d'accueil
            return $this->redirectToRoute('main');
        }

        //Récupération des lieux
        $lieux = $lieuRepo->findAll();

        return $this->render('sortie/creer_sortie.html.twig', [
            'sortie' => $sortie,
            'session' =>$session,
            'lieux' => $lieux,
            'lieuxRattache'=>$tableauDeLieux,
            'form' => $form->createView(),
            'form2' => $form2->createView(),
            'form3' => $form3->createView(),


        ]);
    }


    /**
     * @Route("/sortie/{id}", name="afficher_sortie")
     * @param Sortie $sortie
     * @return Response
     */
    public function afficher_sortie(Sortie $sortie): Response
    {
        //affichage de la page
        return $this->render('sortie/afficher_sortie.html.twig', [
            'sortie' => $sortie
        ]);
    }


    /**
     * @Route("/sortie/{id}/edit", name="modifier_sortie")
     * @param LieuRepository $lieuRepo
     * @param Request $request
     * @param Sortie $sortie
     * @return Response
     */
    public function modifier_sortie(LieuRepository $lieuRepo, Request $request, Sortie $sortie): Response
    {
        //création d'un formulaire sur la base de l'entité Participant
        $form = $this->createForm(SortieType::class, $sortie);

        //hydratation du formualaire
        $form->handleRequest($request);

        //si le formulaire a été soumis
        if($form->isSubmitted() && $form->isValid()) {
            //mise à jour de la Sortie en base
            $this->getDoctrine()->getManager()->flush();

            //redirection vers la page d'affichage de la sortie
            return $this->redirectToRoute('afficher_sortie', ['id' => $sortie->getId()]);
        }
        //Ajoute une liste des lieux
        $lieux = $lieuRepo->findAll();

        //affichage de la page
        return $this->render('sortie/modifier_sortie.html.twig', [
            'sortie' => $sortie,
            'lieux' => $lieux,
            'form' => $form->createView()
        ]);
    }



}
