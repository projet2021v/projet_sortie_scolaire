<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\Sortie;
use App\Repository\InscriptionRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class InscriptionController extends AbstractController
{
    /**
     * @Route("/inscription/{id}", name="inscription")
     * @param Sortie $sortie
     * @param UserInterface $user
     * @param ParticipantRepository $repo_part
     * @return Response
     */
    public function inscription(Sortie $sortie, UserInterface $user, ParticipantRepository $repo_part): Response
    {
        $inscription_possible = true;

        //on vérifie qu'il reste des places
        if($sortie->getInscriptions()->count() >= $sortie->getNbInscriptionsMax()) {
            $inscription_possible = false;
        }

        //on vérifie que l'utilisateur loggé n'est pas déjà inscrit : parcours des inscriptions reliées à la sortie
        foreach($sortie->getInscriptions() as $i) {
            //si l'un de ces inscriptions est celles de l'utilisateur loggé, c'est que celui-ci est déjà inscrit
            if($user->getUsername() == $i->getParticipant()->getUsername()) {
                $inscription_possible = false;
            }
        }

        //si l'utilisateur n'est pas déjà inscrit
        if($inscription_possible) {
            $date_jour = new \DateTime();
            $logged_user = $repo_part->find($user->getId());

            $inscription = new Inscription();
            $inscription->setDateInscription($date_jour);
            $inscription->setParticipant($logged_user);
            $inscription->setSortie($sortie);

            $logged_user->addInscription($inscription);
            $sortie->addInscription($inscription);

            $this->getDoctrine()->getManager()->persist($inscription);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute("main");
    }


    /**
     * @Route("/desinscription/{id}", name="desinscription")
     * @param Sortie $sortie
     * @param UserInterface $user
     * @param InscriptionRepository $repo_inscription
     * @return Response
     */
    public function desinscription(Sortie $sortie, UserInterface $user, InscriptionRepository $repo_inscription): Response
    {
        $desinscription_possible = false;
        $id_inscription = null;

        //on vérifie que l'utilisateur loggé est bien inscrit : parcours des inscriptions reliées à la sortie
        foreach($sortie->getInscriptions() as $i) {
            //si l'un de ces inscriptions est celles de l'utilisateur loggé, c'est que celui-ci est déjà inscrit
            if($user->getUsername() == $i->getParticipant()->getUsername()) {
                $desinscription_possible = true;
                $id_inscription = $i->getId();
//                dd($id_inscription);
            }
        }

        //si l'utilisateur est bien déjà inscrit
        if($desinscription_possible) {
            $inscription = $repo_inscription->find($id_inscription);
            $this->getDoctrine()->getManager()->remove($inscription);
            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute("main");
    }
}
