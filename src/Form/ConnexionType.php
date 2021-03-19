<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConnexionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('identifiant', TextType::class, [
                'mapped' => false,
                'label' => 'Identifiant :',
                'attr' => [
                    'class' => 'form-control'
                ],
                'required' => true,
            ])
            ->add('motDePasse', PasswordType::class, [
                'label' => 'Mot de passe : ',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'password',
                ],

                'required' => true
            ])
            ->add('souvenir', CheckboxType::class, [
                'label' => 'Se souvenir de moi',
                'mapped' => false,
                'required' => false
                ],

            )


        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
