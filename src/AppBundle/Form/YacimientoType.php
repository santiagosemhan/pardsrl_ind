<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YacimientoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('compania', null, [
              'attr' => [ 'class' => 'select2']
            ])
            ->add('nombre')
            ->add('acronimo')
            ->add('localidad', null, [
              'attr' => [ 'class' => 'select2']
            ])
            ->add('activo')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Yacimiento'
        ));
    }
}
