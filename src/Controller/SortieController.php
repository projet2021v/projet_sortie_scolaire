<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="creer_sortie")
     * @param Request $request
     * @param Sortie $sortie
     * @return Response
     */
    public function creer_sortie(Request $request): Response
    {
        $session = $request->getSession();

        $sortie = new Sortie();
        //création d'un formulaire sur la base de l'entité Sortie
        $form = $this->createForm(SortieType::class, $sortie);

        //hydratation du formulaire
        $form->handleRequest($request);

        //si le formulaire a été soumis
        if($form->isSubmitted() && $form->isValid()) {

            //création de la sortie en base
            $em = $this->getDoctrine()->getManager();
            $em->persist($sortie);
            $em->flush();

            //redirection vers la page d'accueil
            return $this->redirectToRoute('main');
        }

        return $this->render('sortie/creer_sortie.html.twig', [
            'sortie' => $sortie,
            'session' =>$session,
            'form' => $form->createView()
        ]);
    }
}
