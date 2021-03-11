<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(SortieRepository $repo): Response
    {
        //$sortie = new Sortie();
        $sorties = $repo->findAll();
        return $this->render('main/index.html.twig', [
            'sorties' => $sorties,
        ]);
    }
}
