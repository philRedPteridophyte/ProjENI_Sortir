<?php

namespace App\Controller;

use App\Entity\Inscriptions;
use App\Entity\Participants;
use App\Entity\Sorties;
use App\Entity\Etats;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SortieType;
use App\Repository\SortiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie', name: 'sortie')]
    public function index(SortiesRepository $sortiesRepository): Response
    {
        return $this->render('sortie/index.html.twig', [
            'sorties' => $sortiesRepository->findAll(),
        ]);
    }

    #[Route('/sortie/create', name: 'createSortie')]
    public function createSortie(EntityManagerInterface $em,Request $request): Response
    {

        $user = $request->getSession()->get('compteConnecte');
        $sortie = new Sorties();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);


        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            if ($sortieForm->get('save')->isClicked()) {
                $etat = $em->getRepository(Etats::class)->findOneBy(['libelle' => 'En cours']);
                $sortie->setEtatsNoEtat($etat);
                $sortie->setEtatsortie($etat->getId());
                $sortie->setOrganisateur($em->getRepository(Participants::class)->findOneBy(['pseudo' => $user->getPseudo()]));
                $em->persist($sortie);
                $em->flush();
            }
            elseif ($sortieForm->get('publish')->isClicked()) {
                $etat = $em->getRepository(Etats::class)->findOneBy(['libelle' => 'En cours']);
                $sortie->setEtatsNoEtat($etat);
                $sortie->setEtatsortie($etat->getId());
                $sortie->setOrganisateur($em->getRepository(Participants::class)->findOneBy(['pseudo' => $user->getPseudo()]));
                $em->persist($sortie);
                $em->flush();
            }
        }

        return $this->render('sortie/form.html.twig', [
            'sortie' => $sortie,
            'form' => $sortieForm->createView(),
        ]);
    }

    #[Route('/sortie/join/{id}', name: 'joinSortie')]
    public function joinSortie(int $id, EntityManagerInterface $em): Response
    {
        // TODO handle real user and dont use this
        $participant = $em->getRepository(Participants::class)->findOneBy(['nom' => 'penaud']);

        $sortie = $em->getRepository(Sorties::class)->findOneBy( ['id' => $id]);
        // TODO handle case when id is not in the database (DAMIEN)

        $isAlreadyIn = $em->getRepository(Inscriptions::class)->findBySortieIdAndParticipants($sortie->getId(),$participant->getId());
        if($isAlreadyIn){
          // TODO handle case when user already joined the Sortie
            echo "deja inscrit";
        }
        else {
            //TODO Remove echo
            echo "vous etes bien inscrit";
            $inscription = new Inscriptions();
            $inscription->setSortiesNoSortieId($sortie->getId());
            $inscription->setParticipantsNoParticipantId($participant->getId());
            $inscription->setDateInscription(new DateTime('now'));

            $em->persist($inscription);
            $em->flush();
        }
        return new Response();
    }

    #[Route('/sortie/leave/{id}', name: 'leaveSortie')]
    public function leaveSortie(int $id, EntityManagerInterface $em): Response
    {
        // TODO handle real user and dont use this
        $participant = $em->getRepository(Participants::class)->findOneBy(['nom' => 'penaud']);

        $sortie = $em->getRepository(Sorties::class)->findOneBy( ['id' => $id]);
        // TODO handle case when id is not in the database (DAMIEN)

        $userIsInSortie = $em->getRepository(Inscriptions::class)->findBySortieIdAndParticipants($sortie->getId(),$participant->getId());
        if($userIsInSortie){
            $em->remove($userIsInSortie);
            $em->flush();
        }
        return new Response();
    }

    #[Route('/sortie/cancel/{id}', name: 'cancelSortie')]
    // En tant qu'organisateur d'une sortie, je peux annuler une sortie si celle-ci n'est pas encore commencée
        // La sortie sera alors marquée comme annulée et sera accompagnée d'un motif d'annulation.
    public function cancelSortie(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        $sortie = $em->getRepository(Sorties::class)->findOneBy( ['id' => $id]);
        // TODO handle case when id is not in the database (DAMIEN)

        if($sortie->getOrganisateur()->getId() == $user->getId()){
            //TODO add date condition in this if ^^^
            $sortie->setEtatsortie($em->getRepository(Etats::class)->findOneBy(['libelle' => 'Annulée'])->getId());
            $sortie->setEtatsNoEtat($em->getRepository(Etats::class)->findOneBy(['libelle' => 'Annulée']));
            $sortie->setDescriptioninfos("Sortie annulée le : ".date_format(new DateTime('now'),'Y-m-d H:i:s'));
            $em->flush();
        }
        return new Response();
    }
}
