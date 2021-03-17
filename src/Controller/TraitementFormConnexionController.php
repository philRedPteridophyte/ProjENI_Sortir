<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TraitementFormConnexionController extends AbstractController
{
    #[Route('/traitementFormConnexion/', name: 'traitement_form_connexion')]
    public function index(): Response
    {
        if(isset($_POST['identifiant']) && isset($_POST["motDePasse"])) {
            $identifiant = htmlspecialchars($_POST['identifiant']);
            $motDePasse =  htmlspecialchars($_POST['motDePasse']);

            if (str_contains($identifiant,"@")) {
                echo "adresse mail";
            }else{
                echo "pseudo";
            }

            echo "identifiant : " .  $identifiant . " mot de passe : " . $motDePasse;

            return $this->render('traitement_form_connexion/index.html.twig', [
                'controller_name' => 'TraitementFormConnexionController',
            ]);
        }else{
            return $this->redirectToRoute('connexion', ['error' => true]);
        }
    }
}
