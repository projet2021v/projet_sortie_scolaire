<?php

namespace App\Controller;

use App\Data\SearchData;
use App\Entity\Site;
use App\Form\SearchType;
use App\Repository\SiteRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/", name="main")
     * @param SortieRepository $repoSortie
     * @return Response
     */
    public function main(SortieRepository $repoSortie, Request $request, UserInterface $user): Response
    {
        $data = new SearchData();
        $form = $this->createForm(SearchType::class, $data);

        $form->handleRequest($request);
        $sorties = $repoSortie->findSearch($data, $user);

        return $this->render('main/index.html.twig', [
            'sorties' => $sorties,
            'form' => $form->createView()
        ]);
    }
}
