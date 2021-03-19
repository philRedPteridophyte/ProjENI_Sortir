<?php

namespace App\Controller;

use App\Entity\Inscriptions;
use App\Entity\Participants;
use App\Entity\Sorties;
use App\Entity\Etats;
use App\Form\SortiesSearchType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie', name: 'sortie')]
    public function index(SortieRepository $sortiesRepository): Response
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

        return $this->render('sortie/createSortie.html.twig', [
            'sortie' => $sortie,
            'form' => $sortieForm->createView(),
        ]);
    }

    #[Route('/sortie/join/{id}', name: 'joinSortie',requirements: [ 'id' => '\d+'])]
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
    public function cancelSortie(int $id, EntityManagerInterface $em): Response
    {
        // TODO handle real user and dont use this
        $participant = $em->getRepository(Participants::class)->findOneBy(['nom' => 'leneveu']);

        $sortie = $em->getRepository(Sorties::class)->findOneBy( ['id' => $id]);
        // TODO handle case when id is not in the database (DAMIEN)

        if($sortie->getOrganisateur()->getId() == $participant->getId()){
            //TODO add date condition in this if ^^^
            $sortie->setEtatsortie($em->getRepository(Etats::class)->findOneBy(['libelle' => 'Annulée'])->getId());
            $sortie->setEtatsNoEtat($em->getRepository(Etats::class)->findOneBy(['libelle' => 'Annulée']));
            $sortie->setDescriptioninfos("Sortie annulée le : ".date_format(new DateTime('now'),'Y-m-d H:i:s'));
            $em->flush();
        }
        return new Response();
    }

    #[Route('/Home', name: 'sorties_0', methods: ['GET','POST'])]
    #[Route('/Sorties', name: 'sorties_1', methods: ['GET','POST'])]
    #[Route('/sorties', name: 'sorties_2', methods: ['GET','POST'])]
    #[Route('/home', name: 'sorties_3', methods: ['GET','POST'])]
    public function recherche(
        Request                      $request,
        SortieRepository       $sortiesRepository
    ): Response
    {
        /*
                if (!$this->isGranted('ROLE_ADMIN') || !$this->isGranted('ROLE_USER')) {
                    throw new AccessDeniedException('Vous n\'avez pas les droits suffisants.');
                }
        */

        //TODO : get user from session
        $user = new Participants();
        //TODO : check roles


        $sorties = null;
        $user = null;
        $lieuxNoLieu = null;
        $nom = null;
        $datedebut = null;
        $datecloture = null;
        $suisOrga = null;
        $inscr = null;
        $pasInscr = null;
        $passee = null;
        $results = null;



        $sortieSearchForm = $this->createForm(SortiesSearchType::class, new Sorties);
        $sortieSearchForm->handleRequest($request);
        //var_dump($sortieSearchForm->get('lieuxNoLieu')->getData()->getId());
        //var_dump($sortieSearchForm->isSubmitted());
        if ($sortieSearchForm->isSubmitted() ) {
            $lieuxNoLieu =  $sortieSearchForm->get('lieuxNoLieu') != null ? $sortieSearchForm->get('lieuxNoLieu')->getData() != null ? $sortieSearchForm->get('lieuxNoLieu')->getData()->getId() : null : null;
            $nom = $sortieSearchForm->get('nom')->getData();
            //var_dump($sortieSearchForm->get('datedebut'));
            $datedebut = $sortieSearchForm->get('datedebut') != null ? $sortieSearchForm->get('datedebut')->getData() : null;
            $datecloture = $sortieSearchForm->get('datecloture') != null ? $sortieSearchForm->get('datecloture')->getData() : null;
            $suisOrga = $sortieSearchForm->get('suisOrga')->getData();
            $inscr = $sortieSearchForm->get('inscr')->getData();
            $pasInscr = $sortieSearchForm->get('pasInscr')->getData();
            $passee = $sortieSearchForm->get('passee')->getData();

            //var_dump([$sorties,$nom,$datedebut,$datecloture,$suisOrga,$inscr,$pasInscr,$passee]);


            $results = $sortiesRepository->filteredSearch( $lieuxNoLieu , $nom, $datedebut, $datecloture, $suisOrga, $inscr, $pasInscr, $passee, $user);

            //var_dump([$results,$lieuxNoLieu,$nom,$datedebut,$datecloture,$suisOrga,$inscr,$pasInscr,$passee]);
            //var_dump($results);
        }


        return $this->render('sortie/chercher.html.twig', [
            'controller_name'   => 'ArticleController'
            ,'results' => $results
            ,'user' => $user
            ,'createArticleForm' => $sortieSearchForm->createView(),
        ]);

    }
}
