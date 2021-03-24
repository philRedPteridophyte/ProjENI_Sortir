<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    #[Route('/lieu', name: 'lieu')]
    public function index(Request $request): Response
    {
        $user = $request->getSession()->get('compteConnecte');
        if($user->getAdministrateur() == false){
            return $this->redirectToRoute('sorties_0');
        }

        return $this->render('lieu/index.html.twig', [
            'controller_name' => 'LieuController',
        ]);
    }
}
