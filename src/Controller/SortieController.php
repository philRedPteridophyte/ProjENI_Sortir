<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\Etat;
use App\Form\SortiesSearchType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\SortieType;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    #[Route('/sortie/{id}', name: 'sortie_get_by_id_0', requirements: [ 'id' => '\d+' ], methods: ['GET'])]
    #[Route('/Sortie/{id}', name: 'sortie_get_by_id_1', requirements: [ 'id' => '\d+' ], methods: ['GET'])]
    public function afficherSortie(?int $id, Request $request, SortieRepository $sortiesRepository): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        $sortie = $sortiesRepository->findOneByIdDetailed($id);

        //var_dump($sortie->getLieu());
        foreach ($sortie->getParticipant() as $p){
        //    var_dump($p);
        }

        if($sortie){
            return $this->render('sortie/read_by_id.html.twig', [
                'sortie' => $sortie,
                'user' => $user,
                'session' => $request->getSession(),
            ]);
        }else{
            return $this->redirect('');
        }
    }

    #[Route('/sortie/create', name: 'createSortie')]
    public function createSortie(EntityManagerInterface $em,Request $request): Response
    {

        if ($request->cookies->has("CookieCompteConnecte")){
            $user = $this->getRequest()->getCookie('CookieCompteConnecte');
        }else if ($request->hasSession() && $request->getSession()->has("compteConnecte")){
            $user = $request->getSession()->get('compteConnecte');
        }else{
            $this->redirectToRoute('connexion');
        }


        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);


        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {
            if ($sortieForm->get('save')->isClicked()) {
                $etat = $em->getRepository(Etat::class)->findOneBy(['libelle' => 'En création']);
                $sortie->setEtat($etat);
                $sortie->setOrganisateur($em->getRepository(Participant::class)->findOneBy(['pseudo' => $user->getPseudo()]));
                $em->persist($sortie);
                $em->flush();
                $this->addFlash('created', 'Votre sortie est créée !');
                return $this->redirectToRoute('sortie_get_by_id_0',['id' => $sortie->getId()]);
            }
            elseif ($sortieForm->get('publish')->isClicked()) {
                $etat = $em->getRepository(Etat::class)->findOneBy(['libelle' => 'En cours']);
                $sortie->setEtat($etat);
                $sortie->setOrganisateur($em->getRepository(Participant::class)->findOneBy(['pseudo' => $user->getPseudo()]));
                $em->persist($sortie);
                $em->flush();
                $this->addFlash('created', 'Votre sortie est créée !');
                return $this->redirectToRoute('sortie_get_by_id_0', ['id' => $sortie->getId()]);
            }
        }
        return $this->render('sortie/createSortie.html.twig', [
            'sortie' => $sortie,
            'user' => $user,
            'form' => $sortieForm->createView(),
        ]);
    }

    #[Route('/sortie/join/{id}', name: 'joinSortie')]
    public function joinSortie(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $participant = $request->getSession()->get("compteConnecte");

        $sortie = $em->getRepository(Sortie::class)->findOneBy( ['id' => $id]);
        // TODO handle case when id is not in the database (DAMIEN)

        if($sortie != null) {
            $isAlreadyIn = $em->getRepository(Inscription::class)->findBySortieIdAndParticipants($sortie->getId(), $participant->getId());
            if ($isAlreadyIn) {
                // TODO handle case when user already joined the Sortie
                echo "deja inscrit";
            } else {
                //TODO Remove echo
                echo "vous etes bien inscrit";
                $inscription = new Inscription();
                $inscription->setSortie($sortie);
                $inscription->setParticipant($em->getRepository(Participant::class)->findOneBy(['id' => $participant->getId()]));
                $inscription->setDateInscription(new DateTime('now'));

                $em->persist($inscription);
                $em->flush();
            }
        }
        else{
            return $this->redirectToRoute('sorties_0');
        }
        $this->addFlash('inscrit', 'Vous êtes inscrit !');
        return $this->redirectToRoute('sortie_get_by_id_0',['id' => $sortie->getId()]);
    }

    #[Route('/sortie/leave/{id}', name: 'leaveSortie')]
    public function leaveSortie(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $participant = $request->getSession()->get("compteConnecte");
        if($participant){
            $sortie = $em->getRepository(Sortie::class)->findOneBy( ['id' => $id]);
            // TODO handle case when id is not in the database (DAMIEN)

            $userIsInSortie = $em->getRepository(Inscription::class)->findBySortieIdAndParticipants($sortie,$participant);
            if($userIsInSortie){
                $em->remove($userIsInSortie);
                $em->flush();
            }
            return $this->redirectToRoute('sorties_0');
        }else{
            return $this->redirectToRoute('connexion');
        }
        return $this->redirectToRoute('connexion');
    }

    #[Route('/sortie/cancel/{id}', name: 'cancelSortie')]
    // En tant qu'organisateur d'une sortie, je peux annuler une sortie si celle-ci n'est pas encore commencée
        // La sortie sera alors marquée comme annulée et sera accompagnée d'un motif d'annulation.
    public function cancelSortie(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        $sortie = $em->getRepository(Sortie::class)->findOneBy( ['id' => $id]);
        if($sortie != null) {
            if ($sortie->getOrganisateur()->getId() == $user->getId()) {
                //TODO add date condition in this if ^^^
                $sortie->setEtat($em->getRepository(Etat::class)->findOneBy(['libelle' => 'Annulée']));
                $sortie->setDescriptioninfos("Sortie annulée le : " . date_format(new DateTime('now'), 'Y-m-d H:i:s'));
                $em->flush();
            }
        }
        else{
            return $this->redirectToRoute('sorties_0');
        }
        $this->addFlash('cancel', 'Votre sortie a bien été annulé.');
        return $this->redirectToRoute('sortie_get_by_id_0',['id' => $sortie->getId()]);
    }

    #[Route('/Home', name: 'sorties_0', methods: ['GET','POST'])]
    #[Route('/Sorties', name: 'sorties_1', methods: ['GET','POST'])]
    #[Route('/sorties', name: 'sorties_2', methods: ['GET','POST'])]
    #[Route('/home', name: 'sorties_3', methods: ['GET','POST'])]
    public function recherche(Request $request, SortieRepository $sortiesRepository): Response
    {
        /*
                if (!$this->isGranted('ROLE_ADMIN') || !$this->isGranted('ROLE_USER')) {
                    throw new AccessDeniedException('Vous n\'avez pas les droits suffisants.');
                }
        */

        $user = $request->getSession()->get("compteConnecte");
        //TODO : check roles
        if($user){
            $sorties = null;
            $lieuxNoLieu = null;
            $nom = null;
            $datedebut = null;
            $datecloture = null;
            $suisOrga = null;
            $inscr = null;
            $pasInscr = null;
            $passee = null;
            $results = $sortiesRepository->findAll();



            $sortieSearchForm = $this->createForm(SortiesSearchType::class, new Sortie);
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
        }else{
            return $this->redirectToRoute('connexion');
        }


    }
}
