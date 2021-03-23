<?php

namespace App\Controller;

use App\Form\GestionType;
use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participant;

class GestionController extends AbstractController
{
    #[Route('/gestion/{id}', name: 'gestion')]
    public function index(Request $request, ?int $id, ParticipantRepository $repository): Response
    {
        $user = $request->getSession()->get('compteConnecte');
        $entityManager = $this->getDoctrine()->getManager();
        $participant = $entityManager->getRepository(Participant::class)->find($id);

        $gestionForm = $this->createForm(GestionType::class, $participant);
        $gestionForm->handleRequest($request);

        if ($gestionForm->isSubmitted() && $gestionForm->isValid()) {
            //TODO Penser verifier la conformité des données update (exemple: je modifie mon mail en un mail déjà utilisé)
            //TODO Idem pour le pseudo
            $entityManager->flush();
        }
        else {
            $this->addFlash('error', 'Les changements n\'ont pas été pris en compte ');
        }
        return $this->render('gestion/index.html.twig', [
            'gestionForm' => $gestionForm->createView(),
            'controller_name' => 'GestionController',
            'user'=>$user,
            ]);
    }
}
