<?php

namespace App\Controller;

use App\Form\GestionType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Participant;

class ParticipantController extends AbstractController
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
        return $this->render('participant/update.html.twig', [
            'gestionForm' => $gestionForm->createView(),
            'controller_name' => 'ParticipantController',
            'user'=>$user,
            ]);
    }
    #[Route('/Participant/{id}', name: 'participant_read_0', requirements: [ 'id' => '\d+' ], methods: ['GET'])]
    public function readById(?int $id, Request $request, ParticipantRepository $repository, EntityManagerInterface $em): Response
    {

        $user = $request->getSession()->get("compteConnecte");
        $session = $request->getSession();
        if($user && $session){
            $participant = $repository->getByIdDetailed($id);
            if($participant){
                $urlPhotoParticipant = $participant->getUrlPhoto();
                if($urlPhotoParticipant == null){
                    $participant->setUrlPhoto("https://i.stack.imgur.com/l60Hf.png");
                    $em->flush();
                }
                $urlPhotoParticipant = $participant->getUrlPhoto();
                return $this->render('participant/read_by_id.html.twig', [
                    'urlPhoto' => $urlPhotoParticipant,
                    'user'  => $user,
                    'session' => $session,
                    'participant' => $participant,
                ]);
            }
        }
        return $this->redirectToRoute('connexion');
    }

    #[Route('/Participant/Deactivate/{id}', name: 'participant_deactivate_0', requirements: [ 'id' => '\d+' ], methods: ['GET'])]
    public function deactivate(?int $id, Request $request, ParticipantRepository $repository): Response
    {

        $user = $request->getSession()->get("compteConnecte");
        $session = $request->getSession();
        if($user && $session){
            $entityManager = $this->getDoctrine()->getManager();
            $participant = $entityManager->getRepository(Participant::class)->find($id);
            if($participant) {
                $user_adm = $repository->getByIdDetailed($user->getId());
                if ($user_adm && $user_adm->getAdministrateur()) {
                    $participant->setActif(false);
                    $entityManager->flush();
                } else {
                    return $this->redirectToRoute('participant_read_0', ['id' => $participant->getId()]);
                }
                return $this->redirectToRoute('sorties_0');
            }
        }
        return $this->redirectToRoute('connexion');
    }

    #[Route('/Participant/Delete/{id}', name: 'participant_delete_0', requirements: [ 'id' => '\d+' ], methods: ['GET'])]
    public function delete(?int $id, Request $request, ParticipantRepository $repository): Response
    {

        $user = $request->getSession()->get("compteConnecte");
        $session = $request->getSession();
        if($user && $session){
            $entityManager = $this->getDoctrine()->getManager();
            $participant = $entityManager->getRepository(Participant::class)->find($id);
            if($participant) {
                $user_adm = $repository->getByIdDetailed($user->getId());
                if ($user_adm && $user_adm->getAdministrateur()) {
                    $entityManager->remove($participant);
                    $entityManager->flush();
                } else {
                    return $this->redirectToRoute('participant_read_0', ['id' => $participant->getId()]);
                }
                return $this->redirectToRoute('sorties_0');
            }
        }
        return $this->redirectToRoute('connexion');
    }
}
