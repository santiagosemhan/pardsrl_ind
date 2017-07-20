<?php

namespace AppBundle\Form;

use Doctrine\DBAL\Query\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Form\IntervencionType;
use AppBundle\Entity\Pozo;
use AppBundle\Entity\Intervencion;

class IntervencionPozoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $intervencion = $event->getData();

            $ultimaIntervencion = $options['ultima_intervencion'];

            $form = $event->getForm();

            // chequeo si la intervencion es un cierre
            if ($intervencion->getAccion() == 1) {

                //Obtengo el pozo al cual se le realizó la última intervención.
                $pozoUltimaIntervencion = $ultimaIntervencion->getPozo();

                $form->add('pozo', EntityType::class, array(
                    'class' => Pozo::class,
                    'data'  => $pozoUltimaIntervencion
                ))
                    ->add('pozoDesc', TextType::class, array(
                        'label'    => 'Pozo',
                        'disabled' => true,
                        'mapped'   => false,
                        'required' => true,
                        'data'     => $pozoUltimaIntervencion->getNombre()
                    ));
            } else {
                $form->add('pozo', EntityType::class, array(
                    'class'    => Pozo::class,
                    'required' => true,
                    'placeholder' => 'Elija un pozo',
                    'choices'  => $options['pozos_elegibles'],
                    'attr'  => ['class' => 'select2']
                ));
            }
        });
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Intervencion::class,
            'pozos_elegibles' => null
        ]);
    }


    public function getParent()
    {
        return IntervencionType::class;
    }
}
