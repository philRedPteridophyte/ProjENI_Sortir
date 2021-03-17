<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participants;
use App\Form\ConnexionType;

class ConnexionController extends AbstractController
{
    #[Route('/', name: 'connexion')]
    public function index(): Response
    {
        $participant = new Participants();
        $connexionForm = $this->createForm(ConnexionType::class, $participant);

        $error = false;
        if(isset($_GET['error']) && $_GET['error'] == 1){
           $error = true;
        }

        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
            'error' => $error,
        ]);
    }
}