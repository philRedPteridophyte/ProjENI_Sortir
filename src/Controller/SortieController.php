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
            $user = $request->cookies->get('CookieCompteConnecte');
        }else if ($request->hasSession() && $request->getSession()->has("compteConnecte")){
            $user = $request->getSession()->get('compteConnecte');
        }else{
            $this->redirectToRoute('connexion');
        }


        $sortie = new Sortie();
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);


        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            //V??rification coh??rence
            $incoherences = array();

            //Date de d??but est apr??s la date du jour
            $dateDebutSortie = date_format($sortieForm->get('datedebut')->getData(),'Y-m-d H:i');
            $now=date_format(new DateTime('now', new \DateTimeZone('Europe/Paris')),'Y-m-d H:i');

            if(strtotime($now) > strtotime($dateDebutSortie)){
                array_push($incoherences,"La sortie n'a pas ??t?? cr????e car la date de d??but est ant??rieur ?? aujourd'hui !");
            }

            //Date cloture est ant??rieur Date d??but
            $dateClotureInscription = date_format($sortieForm->get('datecloture')->getData(),'Y-m-d H:i');
            if(strtotime($dateClotureInscription) > strtotime($dateDebutSortie)){
                array_push($incoherences,"La sortie n'a pas ??t?? cr????e car la date de cloture est post??rieur ?? la date de d??but de la sortie !");
            }

            if(sizeof($incoherences) > 0){
                foreach ($incoherences as $incoherence){
                    $this->addFlash('erreurCreation', $incoherence);
                }
                return $this->redirectToRoute('createSortie');
            }
            else{
                if ($sortieForm->get('save')->isClicked()) {
                    $etat = $em->getRepository(Etat::class)->findOneBy(['libelle' => 'En cr??ation']);
                    $sortie->setEtat($etat);
                    $sortie->setOrganisateur($em->getRepository(Participant::class)->findOneBy(['pseudo' => $user->getPseudo()]));
                    $em->persist($sortie);
                    $em->flush();
                    $this->addFlash('created', 'Votre sortie est cr????e !');
                    return $this->redirectToRoute('sortie_get_by_id_0',['id' => $sortie->getId()]);
                }
                elseif ($sortieForm->get('publish')->isClicked()) {
                    $etat = $em->getRepository(Etat::class)->findOneBy(['libelle' => 'En cours']);
                    $sortie->setEtat($etat);
                    $sortie->setOrganisateur($em->getRepository(Participant::class)->findOneBy(['pseudo' => $user->getPseudo()]));
                    $em->persist($sortie);
                    $em->flush();
                    $this->addFlash('created', 'Votre sortie est cr????e !');
                    return $this->redirectToRoute('sortie_get_by_id_0', ['id' => $sortie->getId()]);
                }
            }
        }
        return $this->render('sortie/createSortie.html.twig', [
            'sortie' => $sortie,
            'user' => $user,
            'session' => $request->getSession(),
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
            if($sortie->getEtat()->getId() == 2 || $sortie->getEtat()->getId() == 5 || $sortie->getEtat()->getId() == 6){
                return $this->redirectToRoute('sorties_0');
            }

            if(date_format($sortie->getDatecloture(),'Y-m-d H:i') <= date_format(new DateTime('now', new \DateTimeZone('Europe/Paris')),'Y-m-d H:i')){
                return $this->redirectToRoute('sorties_0');
            }

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
        $this->addFlash('inscrit', 'Vous ??tes inscrit !');
        return $this->redirectToRoute('sortie_get_by_id_0',['id' => $sortie->getId()]);
    }

    #[Route('/sortie/leave/{id}', name: 'leaveSortie')]
    public function leaveSortie(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $participant = $request->getSession()->get("compteConnecte");
        if($participant){
            $sortie = $em->getRepository(Sortie::class)->findOneBy( ['id' => $id]);

            if($sortie == null){
                return $this->redirectToRoute('sorties_0');
            }
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
    public function cancelSortie(int $id, EntityManagerInterface $em, Request $request): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        $sortie = $em->getRepository(Sortie::class)->findOneBy( ['id' => $id]);

        if($sortie != null) {

            if($user->getAdministrateur() && $sortie->getEtat()->getId() == 6){
                return $this->redirectToRoute('sorties_0');
            }

            if(!$user->getAdministrateur() && $sortie->getOrganisateur() != $user){
                return $this->redirectToRoute('sorties_0');
            }

            if (($sortie->getOrganisateur()->getId() == $user->getId()) || $user->getAdministrateur()) {
                //TODO add date condition in this if ^^^
                $sortie->setEtat($em->getRepository(Etat::class)->findOneBy(['libelle' => 'Annul??e']));
                $sortie->setDescriptioninfos("Sortie annul??e le : " . date_format(new DateTime('now', new \DateTimeZone("Europe/Paris")), 'Y-m-d H:i:s'));
                $em->flush();
            }
        }
        else{
            return $this->redirectToRoute('sorties_0');
        }
        $this->addFlash('cancel', 'Votre sortie a bien ??t?? annul??.');
        return $this->redirectToRoute('sortie_get_by_id_0',['id' => $sortie->getId()]);
    }

    #[Route('/Home', name: 'sorties_0', methods: ['GET','POST'])]
    #[Route('/Sorties', name: 'sorties_1', methods: ['GET','POST'])]
    #[Route('/sorties', name: 'sorties_2', methods: ['GET','POST'])]
    #[Route('/home', name: 'sorties_3', methods: ['GET','POST'])]
    public function recherche(Request $request, SortieRepository $sortiesRepository): Response
    {

        $user = $request->getSession()->get("compteConnecte");

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
            $results = null;



            $sortieSearchForm = $this->createForm(SortiesSearchType::class, new Sortie);
            $sortieSearchForm->handleRequest($request);
            if ($sortieSearchForm->isSubmitted() ) {
                $lieuxNoLieu =  $sortieSearchForm->get('lieuxNoLieu') != null ? $sortieSearchForm->get('lieuxNoLieu')->getData() != null ? $sortieSearchForm->get('lieuxNoLieu')->getData()->getId() : null : null;
                $nom = $sortieSearchForm->get('nom')->getData();
                $datedebut = $sortieSearchForm->get('datedebut') != null ? $sortieSearchForm->get('datedebut')->getData() : null;
                $datecloture = $sortieSearchForm->get('datecloture') != null ? $sortieSearchForm->get('datecloture')->getData() : null;
                $suisOrga = $sortieSearchForm->get('suisOrga')->getData();
                $inscr = $sortieSearchForm->get('inscr')->getData();
                $pasInscr = $sortieSearchForm->get('pasInscr')->getData();
                $passee = $sortieSearchForm->get('passee')->getData();

                $results = $sortiesRepository->filteredSearch( $lieuxNoLieu , $nom, $datedebut, $datecloture, $suisOrga, $inscr, $pasInscr, $passee, $user);

            }else{
                $results = $sortiesRepository->filteredSearch( $lieuxNoLieu , $nom, $datedebut, $datecloture, $suisOrga, $inscr, $pasInscr, $passee, $user);
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
