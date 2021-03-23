<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Repository\LieuxRepository;
use App\Repository\SiteRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{

    public function __construct(VilleRepository $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => "Nom de la sortie",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('datedebut', DateType::class, [
                'label' => "Date de début de la sortie",
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('datecloture', DateType::class, [
                'label' => "Date limite d'inscription",
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('nbinscriptionsmax', NumberType::class, [
                'label' => "Nombre de places",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('duree', NumberType::class, [
                'label' => "Durée",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('descriptioninfos', TextareaType::class, [
                'label' => "Description et infos",
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('lieu', EntityType::class, [
                'label' => "Lieu",
                'class' => Lieu::class,
                'choice_label' => function ($lieu) {
                    $villeLieu = $this->em->findOneBy(['id' => $lieu->getVille()]);
                    return $lieu->getNomLieu()." -- ".strtoupper($villeLieu->getNomVille());
                },
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer le brouillon',
                'attr' => [
                    'class' => 'btn btn-info'
                ]
            ])
            ->add('publish', SubmitType::class, [
                'label' => 'Publier la sortie',
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ])
            /*
            ->add('cancel', ResetType::class, [
                'label' => 'Annuler',
                'attr' => [
                    'class' => 'btn btn-danger'
                ]
            ])
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
