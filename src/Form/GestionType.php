<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Site;
use App\Repository\LieuxRepository;
use App\Repository\SiteRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GestionType extends AbstractType
{
    private $em;

    /**
     * @param SiteRepository $em
     */
    public function __construct(SiteRepository $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $sitesrepo = $this->em;//->getRepository('App\Repository\SiteRepository');
        $listSites = $sitesrepo->createQueryBuilder('l')
            ->getQuery()
            ->getResult();

        $builder
            ->add('site', EntityType::class, [
                'label' => 'Site'
                ,'class'=> Site::class
                ,'choice_label' => 'nomSite'
                ,'expanded' => false
                ,'multiple' => false
                ,'required' => true
                ,'choices' => $listSites
                ,'mapped' => true
            ] )
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'required' => false
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'required' => false
            ])
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'required' => false
            ])
            ->add('telephone', TextType::class, [
                'label' => 'Téléphone',
                'required' => false
            ])
            ->add('mail', TextType::class, [
                'label' => 'Mail',
                'required' => false
            ])
            ->add('motDePasse', RepeatedType::class, [
                'type' => TextType::class,
                'invalid_message' => 'Les mots de passe doivent être identiques.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Répéter le mot de passe'],
            ])
            ->add('urlPhoto',TextType::class, [
                'label' => 'Url de photo de profil',
                'required' => false
            ])
            ->add('publish', SubmitType::class, [
                'label' => 'Confirmer les changements',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            ->add('cancel', ResetType::class, [
                'label' => 'Annuler',
                'attr' => [
                    'class' => 'btn btn-danger'
                ]
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
