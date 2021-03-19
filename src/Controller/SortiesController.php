<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Entity\Sorties;
use App\Form\SortiesSearchType;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortiesController extends AbstractController
{
    #[Route('/Home', name: 'sorties_0', methods: ['GET','POST'])]
    #[Route('/Sorties', name: 'sorties_1', methods: ['GET','POST'])]
    #[Route('/sorties', name: 'sorties_2', methods: ['GET','POST'])]
    #[Route('/home', name: 'sorties_3', methods: ['GET','POST'])]
    public function recherche(
        Request                      $request,
        SortiesRepository       $sortiesRepository
    ): Response
    {
        /*
                if (!$this->isGranted('ROLE_ADMIN') || !$this->isGranted('ROLE_USER')) {
                    throw new AccessDeniedException('Vous n\'avez pas les droits suffisants.');
                }
        */

        //TODO : get user from session
        $user = new Participants();
        //TODO : check roles


        $sorties = null;
        $user = null;
        $lieuxNoLieu = null;
        $nom = null;
        $datedebut = null;
        $datecloture = null;
        $suisOrga = null;
        $inscr = null;
        $pasInscr = null;
        $passee = null;
        $results = null;



        $sortieSearchForm = $this->createForm(SortiesSearchType::class, new Sorties);
        $sortieSearchForm->handleRequest($request);
        //var_dump($sortieSearchForm->get('lieuxNoLieu')->getData()->getId());
        //var_dump($sortieSearchForm->isSubmitted());
        if ($sortieSearchForm->isSubmitted() ) {
            $lieuxNoLieu =  $sortieSearchForm->get('lieuxNoLieu') != null ? $sortieSearchForm->get('lieuxNoLieu')->getData() != null ? $sortieSearchForm->get('lieuxNoLieu')->getData()->getId() : null : null;
            $nom = $sortieSearchForm->get('nom')->getData();
            //var_dump($sortieSearchForm->get('datedebut'));
            $datedebut = $sortieSearchForm->get('datedebut') != null ? $sortieSearchForm->get('datedebut')->getData() : null;
            $datecloture = $sortieSearchForm->get('datecloture') != null ? $sortieSearchForm->get('datecloture')->getData() : null;
            $suisOrga = $sortieSearchForm->get('suisOrga')->getData();
            $inscr = $sortieSearchForm->get('inscr')->getData();
            $pasInscr = $sortieSearchForm->get('pasInscr')->getData();
            $passee = $sortieSearchForm->get('passee')->getData();

            //var_dump([$sorties,$nom,$datedebut,$datecloture,$suisOrga,$inscr,$pasInscr,$passee]);


            $results = $sortiesRepository->filteredSearch( $lieuxNoLieu , $nom, $datedebut, $datecloture, $suisOrga, $inscr, $pasInscr, $passee, $user);

            //var_dump([$results,$lieuxNoLieu,$nom,$datedebut,$datecloture,$suisOrga,$inscr,$pasInscr,$passee]);
            //var_dump($results);
        }


        return $this->render('sorties/chercher.html.twig', [
            'controller_name'   => 'ArticleController'
            ,'results' => $results
            ,'user' => $user
            ,'createArticleForm' => $sortieSearchForm->createView(),
        ]);

    }
}
