<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Form\LieuSearchType;
use App\Form\LieuEditType;
use App\Form\LieuAddType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    #[Route('/lieu', name: 'lieu')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        if($user->getAdministrateur() == false){
            return $this->redirectToRoute('sorties_0');
        }


        $lieuSearchForm = $this->createForm(LieuSearchType::class, new Lieu());
        $lieuSearchForm->handleRequest($request);

        $lieux = $em->getRepository(Lieu::class)->findAll();

        if ($lieuSearchForm->isSubmitted() && $lieuSearchForm->isValid()) {
            if ($lieuSearchForm->get('submit')->isClicked()) {
                $lieux = $em->getRepository(Lieu::class)->findAllThatContains($lieuSearchForm->get('nomLieu')->getData());
            }
        }

        return $this->render('lieu/index.html.twig', [
            'controller_name' => 'LieuController',
            'user' => $user,
            'lieuSearchForm' => $lieuSearchForm->createView(),
            'lieux' => $lieux
        ]);
    }

    #[Route('/lieu/edit/{id}', name: 'editLieu')]
    public function editLieu(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        if($user->getAdministrateur() == false){
            return $this->redirectToRoute('sorties_0');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $lieu = $entityManager->getRepository(Lieu::class)->find($id);

        $lieuEditForm = $this->createForm(LieuEditType::class, $lieu);
        $lieuEditForm->handleRequest($request);

        $lieuToEdit = $em->getRepository(Lieu::class)->findOneBy( ['id' => $id]);

        if ($lieuEditForm->isSubmitted() && $lieuEditForm->isValid()) {
            if ($lieuEditForm->get('submit')->isClicked()) {
                $lieuToEdit->setNomLieu($lieuEditForm->get('nomLieu')->getData());
                $lieuToEdit->setRue($lieuEditForm->get('rue')->getData());
                $lieuToEdit->setLatitude($lieuEditForm->get('latitude')->getData());
                $lieuToEdit->setLongitude($lieuEditForm->get('longitude')->getData());
                $lieuToEdit->setVille($lieuEditForm->get('ville')->getData());
                $em->flush();
                $this->addFlash('success', 'Les changements ont bien été pris en compte');
                return $this->redirectToRoute('lieu', $request->query->all());
            }
        }
        else{
            $lieuEditForm->get('nomLieu')->setData($lieuToEdit->getNomLieu());
        }

        return $this->render('lieu/editLieu.html.twig', [
            'user' => $user,
            'lieuEditForm' => $lieuEditForm->createView(),
            'lieuToEdit' => $lieuToEdit
        ]);
    }

    #[Route('/lieu/add', name: 'addLieu')]
    public function addLieu(Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        if($user->getAdministrateur() == false){
            return $this->redirectToRoute('sorties_0');
        }

        $lieuAddForm = $this->createForm(LieuAddType::class, new Lieu());
        $lieuAddForm->handleRequest($request);

        $lieuToAdd = new Lieu();

        if ($lieuAddForm->isSubmitted() && $lieuAddForm->isValid()) {
            if ($lieuAddForm->get('submit')->isClicked()) {
                $lieuToAdd->setNomLieu($lieuAddForm->get('nomLieu')->getData());
                $lieuToAdd->setRue($lieuAddForm->get('rue')->getData());
                $lieuToAdd->setLatitude($lieuAddForm->get('latitude')->getData());
                $lieuToAdd->setLongitude($lieuAddForm->get('longitude')->getData());
                $lieuToAdd->setVille($lieuAddForm->get('ville')->getData());
                $em->persist($lieuToAdd);
                $em->flush();
                $this->addFlash('success', 'Le lieu a été ajouté');
                return $this->redirectToRoute('lieu', $request->query->all());
            }
        }

        return $this->render('lieu/addLieu.html.twig', [
            'user' => $user,
            'lieuAddForm' => $lieuAddForm->createView(),
        ]);
    }
}
