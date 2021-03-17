<?php

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocalDateTimeType extends AbstractType
{
    const HTML5_FORMAT = "yyyy-MM-dd'T'HH:mm";

    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['widget'] = $options['widget'];

        //if ($options['html5'] && 'single_text' === $options['widget'] && self::HTML5_FORMAT === $options['format']) {
        $view->vars['type'] = 'datetime-local';
        //}
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'format' => self::HTML5_FORMAT
            /*
            ,'widget'=>'single_text'
            ,'html5' => false
            */
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return DateTimeType::class;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'local_datetime';
    }
}