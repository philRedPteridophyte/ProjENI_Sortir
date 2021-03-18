<?php

namespace App\Form;

use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Repository\LieuxRepository;
use App\Form\Type\LocalDateTimeType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortiesSearchType extends AbstractType
{
    private $em;

    /**
     * @param LieuxRepository $em
     */
    public function __construct(LieuxRepository $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $lieuxrepo = $this->em;//->getRepository('App\Repository\LieuxRepository');
        $listLieux = $lieuxrepo->createQueryBuilder('l')
                ->getQuery()
                ->getResult();
        $builder
            ->setMethod('POST')

            ->add('lieuxNoLieu', EntityType::class, options: [
                'label' => 'Site'
                ,'class'=> Lieux::class
                ,'choice_label' => 'nomLieu'
                ,'expanded' => false
                ,'multiple' => false
                ,'required' => false
                ,'choices' => $listLieux
                ,'mapped' => false

            ] )
            ->add('nom', options: [
                'label' => 'Nom partiel'
                ,'required' => false
                ,'mapped' => false
            ] )
            ->add('datedebut',LocalDateTimeType::class ,[
                'widget' => 'single_text'
                ,'html5' => false
                ,'label' => 'Entre'
                ,'required' => false
                ,'mapped' => false
            ] )
            ->add('datecloture',LocalDateTimeType::class ,[
                'widget' => 'single_text'
                ,'html5' => false
                ,'label' => 'et'
                ,'required' => false
                ,'mapped' => false
            ] )
            ->add('suisOrga', CheckboxType::class,[
                'label' => 'Sorties dont je suis l\'organisa(teur/trice)'
                ,'mapped' => false
                ,'required' => false
            ] )
            ->add('inscr', CheckboxType::class,[
                'label' => 'Sorties auxquelles je suis inscrit(/e)'
                ,'mapped' => false
                ,'required' => false
            ] )
            ->add('pasInscr', CheckboxType::class,[
                'label' => 'Sorties auxquelles je ne suis pas inscrit(/e)'
                ,'mapped' => false
                ,'required' => false
            ] )
            ->add('passee', CheckboxType::class,[
                'label' => 'Sorties passées )'
                ,'mapped' => false
                ,'required' => false
            ] )
        ;
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sorties::class,
        ]);
    }
}
