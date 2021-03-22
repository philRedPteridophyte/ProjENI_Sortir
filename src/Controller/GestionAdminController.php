<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Ville;
use App\Form\AdminAddUserType;
use App\Form\VilleAddType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionAdminController extends AbstractController
{
    #[Route('/gestion/admin', name: 'gestion_admin')]
    public function index(Request $request): Response
    {

        $user = $request->getSession()->get('compteConnecte');

        return $this->render('gestion_admin/index.html.twig', [
            'controller_name' => 'GestionAdminController',
            'user' => $user,
        ]);
    }

    #[Route('/gestion/addUser', name: 'adminAddUser')]
    public function addUser(Request $request, EntityManagerInterface $em): Response
    {

        $user = $request->getSession()->get('compteConnecte');

        $adminAddUserForm =$this->createForm(AdminAddUserType::class, new Participant());
        $adminAddUserForm->handleRequest($request);

        if ($adminAddUserForm->isSubmitted() && $adminAddUserForm->isValid()) {
            if ($adminAddUserForm->get('submit')->isClicked()) {
                $newUser = new Participant();
                $newUser->setPseudo($adminAddUserForm->get('pseudo')->getData());
                $newUser->setNom($adminAddUserForm->get('nom')->getData());
                $newUser->setPrenom($adminAddUserForm->get('prenom')->getData());
                $newUser->setTelephone($adminAddUserForm->get('telephone')->getData());
                $newUser->setMail($adminAddUserForm->get('mail')->getData());
                $newUser->setMotDePasse($adminAddUserForm->get('motDePasse')->getData());
                $newUser->setAdministrateur($adminAddUserForm->get('administrateur')->getData());
                $newUser->setActif(true);
                $newUser->setSite($adminAddUserForm->get('site')->getData());
                $em->persist($newUser);
                $em->flush();
                return $this->redirectToRoute('gestion_admin', $request->query->all());
            }
        }

        return $this->render('gestion_admin/admin_add_user.html.twig', [
            'controller_name' => 'GestionAdminController',
            'user' => $user,
            'adminAddUser' => $adminAddUserForm->createView(),
        ]);
    }
}
