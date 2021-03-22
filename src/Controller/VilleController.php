<?php

namespace App\Controller;

use App\Entity\Ville;
use App\Form\LieuxSearchType;
use App\Form\VilleSearchType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    #[Route('/ville', name: 'ville')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        $villeSearchForm = $this->createForm(VilleSearchType::class, new Ville());
        $villeSearchForm->handleRequest($request);

        $villes = $em->getRepository(Ville::class)->findAll();

        if ($villeSearchForm->isSubmitted() && $villeSearchForm->isValid()) {
            if ($villeSearchForm->get('submit')->isClicked()) {
                $villes = $em->getRepository(Ville::class)->findAllThatContains($villeSearchForm->get('nomVille')->getData());
            }
        }

        return $this->render('ville/index.html.twig', [
            'controller_name' => 'VilleController',
            'user' => $user,
            'villeSearchForm' => $villeSearchForm->createView(),
            'villes' => $villes
        ]);
    }
}
