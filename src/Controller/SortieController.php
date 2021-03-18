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
        $sortie = new Sorties();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);


        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            if ($sortieForm->get('save')->isClicked()) {
                $etat = $em->getRepository(Etats::class)->findOneBy(['libelle' => 'En cours']);
                $sortie->setEtatsNoEtat($etat);
                $sortie->setOrganisateur($em->getRepository(Participants::class)->findOneBy(['nom' => 'leneveu']));
                $em->persist($sortie);
                $em->flush();
            }
            elseif ($sortieForm->get('publish')->isClicked()) {
                $etat = $em->getRepository(Etats::class)->findOneBy(['libelle' => 'En cours']);
                $sortie->setEtatsNoEtat($etat);
                $sortie->setOrganisateur($em->getRepository(Participants::class)->findOneBy(['nom' => 'leneveu']));
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
    public function joinSortie(int $id, EntityManagerInterface $em, Request $request): Response
    {
        // TODO handle real user and dont use this
        $participant = new Participants();
        $participant = $em->getRepository(Participants::class)->findOneBy(['nom' => 'penaud']);

        $sortie = $em->getRepository(Sorties::class)->findOneBy( ['id' => "1"]);
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
}
