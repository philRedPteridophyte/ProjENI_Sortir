<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Entity\Sortie;
use App\Entity\Ville;
use App\Form\LieuxSearchType;
use App\Form\VilleAddType;
use App\Form\VilleEditType;
use App\Form\VilleSearchType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    #[Route('/ville', name: 'ville')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        if($user->getAdministrateur() == false){
            return $this->redirectToRoute('sorties_0');
        }


        $villeSearchForm = $this->createForm(VilleSearchType::class, new Ville());
        $villeSearchForm->handleRequest($request);

        $villes = $em->getRepository(Ville::class)->findAll();

        if ($villeSearchForm->isSubmitted() && $villeSearchForm->isValid()) {
            if ($villeSearchForm->get('submit')->isClicked()) {
                $villes = $em->getRepository(Ville::class)->findAllThatContains($villeSearchForm->get('nomVille')->getData());
            }
        }

        return $this->render('ville/index.html.twig', [
            'controller_name' => 'VilleController',
            'user' => $user,
            'villeSearchForm' => $villeSearchForm->createView(),
            'villes' => $villes
        ]);
    }

    #[Route('/ville/edit/{id}', name: 'editVille')]
    public function editVille(int $id,Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        if($user->getAdministrateur() == false){
            return $this->redirectToRoute('sorties_0');
        }

        $villeEditForm = $this->createForm(VilleEditType::class, new Ville());
        $villeEditForm->handleRequest($request);

        $villeToEdit = $em->getRepository(Ville::class)->findOneBy( ['id' => $id]);



        if ($villeEditForm->isSubmitted() && $villeEditForm->isValid()) {
            if ($villeEditForm->get('submit')->isClicked()) {
                $villeToEdit->setNomVille($villeEditForm->get('nomVille')->getData());
                $villeToEdit->setCodePostal($villeEditForm->get('codePostal')->getData());
                $em->flush();
            }
        }
        else{
            $villeEditForm->get('nomVille')->setData($villeToEdit->getNomVille());
            $villeEditForm->get('codePostal')->setData($villeToEdit->getCodePostal());
        }

        return $this->render('ville/editVille.html.twig', [
            'user' => $user,
            'villeEditForm' => $villeEditForm->createView(),
            'villeToEdit' => $villeToEdit
        ]);
    }

    #[Route('/ville/add', name: 'addVille')]
    public function addVille(Request $request, EntityManagerInterface $em): Response
    {
        $user = $request->getSession()->get('compteConnecte');

        if($user->getAdministrateur() == false){
            return $this->redirectToRoute('sorties_0');
        }

        $villeAddForm = $this->createForm(VilleAddType::class, new Ville());
        $villeAddForm->handleRequest($request);

        $villeToAdd = new Ville();

        if ($villeAddForm->isSubmitted() && $villeAddForm->isValid()) {
            if ($villeAddForm->get('submit')->isClicked()) {
                $villeToAdd->setNomVille($villeAddForm->get('nomVille')->getData());
                $villeToAdd->setCodePostal($villeAddForm->get('codePostal')->getData());
                $em->persist($villeToAdd);
                $em->flush();
                return $this->redirectToRoute('ville', $request->query->all());
            }
        }

        return $this->render('ville/addVille.html.twig', [
            'user' => $user,
            'villeAddForm' => $villeAddForm->createView(),
        ]);
    }
}
