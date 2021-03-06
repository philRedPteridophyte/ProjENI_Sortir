<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Site;
use App\Form\AdminAddUserType;
use App\Form\UploadCSVType;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Array_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GestionAdminController extends AbstractController
{
    #[Route('/gestion/admin', name: 'gestion_admin')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $csvForm = $this->createForm(UploadCSVType::class);
        $csvForm->handleRequest($request);

        $user = $request->getSession()->get('compteConnecte');

        if($user->getAdministrateur() == false){
            return $this->redirectToRoute('sorties_0');
        }

        if ($csvForm->isSubmitted() && $csvForm->isValid()) {
            $csvFile = $csvForm['csv']->getData();
            if ($csvFile) {
                $newFilename = 'importUser.txt';
                try {
                    $csvFile->move(
                    //Path parameter is in "services.yaml"
                        $this->getParameter('csv_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash("error","Erreur pendant l'import du fichier");
                }
            }
            $pathCSV = $this->getParameter('csv_directory');
            if (($handle = fopen($pathCSV . '/importUser.txt', 'r')) !== FALSE) {
                //On boucle sur chaque ligne du csv
                fgetcsv($handle,1000,";"); // skip first line
                $flag = false;
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    if (!(strlen($data[0]) > 0 && strlen($data[0]) < 255) ||
                        !(strlen($data[1]) > 0 && strlen($data[1]) < 255) ||
                        !(strlen($data[2]) > 0 && strlen($data[2]) < 255) ||
                        !(strlen($data[3]) > 0 && strlen($data[3]) < 255) ||
                        !(strlen($data[4]) > 0 && strlen($data[4]) < 255) ||
                        !(strlen($data[5]) > 0 && strlen($data[5]) < 255) ||
                        !($data[6] == 0 || $data[6] == 1) ||
                        !($data[7] == 0 || $data[7] == 1)) {
                        $this->addFlash("error", "le fichier csv ne respecte pas le standard d'import");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }

                    if(!ctype_alnum($data[0])){
                        $this->addFlash("error", "Le format du pseudo ".$data[0]." est erron??");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }

                    if(!ctype_alpha($data[1])){
                        $this->addFlash("error", "Le format du nom ".$data[1]." est erron??");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }


                    if(!ctype_alpha($data[2])){
                        $this->addFlash("error", "Le format du prenom ".$data[2]." est erron??");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }

                    $telephone = preg_match("/^[0-9]{10}+$/", $data[3]);

                    if(!$telephone){
                        $this->addFlash("error", "Le format du tel ".$data[3]." est erron??");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }

                    $mail = filter_var($data[4], FILTER_VALIDATE_EMAIL);

                    if(!$mail ){
                        $this->addFlash("error", "Le format du mail ".$data[4]." est erron??");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }


                    if($data[6] != 0 && $data[6] != 1 ){
                        $this->addFlash("error", "Admin doit ??tre 1 ou 0");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }

                    if($data[7] != 0 && $data[7] != 1 ){
                        $this->addFlash("error", "Actif doit ??tre 1 ou 0");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }

                    if ($em->getRepository(Site::class)->findOneBy(['id' => $data[8]]) == null) {
                        $this->addFlash("error", "le site : $data[8] n'existe pas");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }
                    if ($em->getRepository(Participant::class)->findOneBy(['mail' => $data[4]]) != null) {
                        $this->addFlash("error", "le mail  $data[4] est d??ja utilis??");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }
                    if ($em->getRepository(Participant::class)->findOneBy(['pseudo' => $data[0]]) != null) {
                        $this->addFlash("error", "le pseudo  $data[0] est d??ja utilis??");
                        $flag = true;
                        $this->redirectToRoute("gestion_admin");
                    }
                }
            }
            if($flag == false) {
                if (($handle = fopen($pathCSV . '/importUser.txt', 'r')) !== FALSE) {
                    //On boucle sur chaque ligne du csv
                    while (($data = fgetcsv($handle, 0, ";")) !== FALSE) {
                        $site = $em->getRepository(Site::class)->findOneBy(['id' => $data[8]]);
                        if ($site != null) {
                            $participant = new Participant();
                            $participant->setPseudo($data[0])
                                ->setNom($data[1])
                                ->setPrenom($data[2])
                                ->setTelephone($data[3])
                                ->setMail($data[4])
                                ->setAdministrateur($data[6])
                                ->setActif($data[7])
                                ->setSite($site)
                                ->setMotDePasse($data[5]);
                            $em->persist($participant);
                        }
                    }
                    $em->flush();
                    unlink($pathCSV . '/importUser.txt');
                    $this->addFlash("success", "L'import a ??t?? effectu?? avec succ??s");
                }
            }
        }

        $user = $request->getSession()->get('compteConnecte');

        return $this->render('gestion_admin/index.html.twig', [
            'controller_name' => 'GestionAdminController',
            'csvForm' => $csvForm->createView(),
            'session' => $request->getSession(),
            'user' => $user,
        ]);
    }

    #[Route('/gestion/addUser', name: 'adminAddUser')]
    public function addUser(Request $request, EntityManagerInterface $em): Response
    {

        $user = $request->getSession()->get('compteConnecte');

        if($user->getAdministrateur() == false){
            return $this->redirectToRoute('sorties_0');
        }

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
            'session' =>$request->getSession(),
            'adminAddUser' => $adminAddUserForm->createView(),
        ]);
    }
}
