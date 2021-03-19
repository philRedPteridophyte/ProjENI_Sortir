<?php

namespace App\Controller;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participant;
use App\Form\ConnexionType;

class ConnexionController extends AbstractController
{
    #[Route('/', name: 'connexion')]
    public function index(Request $request): Response
    {
        //Instance des sessions:
        $session = $request->getSession();
        //$session->start();

        //Appel du formulaire relié à l'entité Participant
        $participant = new Participant();
        $connexionForm = $this->createForm(ConnexionType::class, $participant);

        //Vérification de l'envoie du formaulaire
        $connexionForm->handleRequest($request);

        //Debugage vérif de saisie:
        //dump($participant);

        if ($connexionForm->isSubmitted() && $connexionForm->isValid()) {

            //Récupération des variables du Repesitory  et vérification de si exist en base
            $identifiant = htmlentities($connexionForm->get("identifiant")->getData());
            $mot_de_passe = htmlentities($connexionForm->get("motDePasse")->getData());
            $participantRepo = $this->getDoctrine()->getRepository(Participant::class);
            $participantEnBase = $participantRepo->findIfExist($identifiant,$mot_de_passe);

            //Si les informations existes
            if($participantEnBase != null){
               $this->addFlash('success', 'Vous êtes bien connecté en tant que ' . $participantEnBase->getPseudo() . ' :) ');
               $session->set('compteConnecte', $participantEnBase);
               // On envoie vers la page main
               return $this->redirectToRoute("test");

            }else {
                $this->addFlash('error', 'Erreur : identifiant/email ou mot de passe incorrect(s). Veuillez ré-essayer ou bien cliquez sur mot de passe oublié');
                $this->redirectToRoute('connexion');
            }

            //Si les informations existes pas
                //On ré-affiche le formulaire avec l'erreur

            // Débugage de vérification si les informations existent en base
            dump($participantEnBase);
        }

        return $this->render('connexion/index.html.twig', [
            'connexionForm' => $connexionForm->createView(),
            'controller_name' => 'ConnexionController',
        ]);
    }
}