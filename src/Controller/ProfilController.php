<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="afficher_profil")
     * @param Participant $participant
     * @return Response
     */
    public function afficherProfil(Participant $participant): Response
    {
        //affichage de la page
        return $this->render('profil/afficher_profil.html.twig', [
            'participant' => $participant
        ]);
    }


    /**
     * @Route("/profil/{id}/edit", name="modifier_profil")
     * @param Request $request
     * @param Participant $participant
     * @return Response
     */
    public function modifierProfil(Request $request, Participant $participant): Response
    {
        //création d'un formulaire sur la base de l'entité Participant
        $form = $this->createForm(ParticipantType::class, $participant);

        //hydratation du formualaire
        $form->handleRequest($request);

        //si le formulaire a été soumis
        if($form->isSubmitted() && $form->isValid()) {

            //mise à jour du Participant en base
            $this->getDoctrine()->getManager()->flush();

            //redirection vers la page d'affichage du profil
            return $this->redirectToRoute('afficher_profil', ['id' => $participant->getId()]);
        }

        //affichage de la page et du formulaire
        return $this->render('profil/modifier_profil.html.twig', [
            'participant' => $participant,
            'form' => $form->createView()
        ]);
    }
}
