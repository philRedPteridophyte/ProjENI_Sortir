<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

class TestController extends AbstractController
{
    #[Route('/test/', name: 'test')]
    public function index(Request $request): Response
    {
        $session = $request->getSession();
        $pseudo = $session->get('compteConnecte');

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
            'session' => $pseudo
        ]);
    }
}
