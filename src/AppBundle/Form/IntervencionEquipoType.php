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
use AppBundle\Entity\Equipo;
use AppBundle\Entity\Intervencion;

class IntervencionEquipoType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $intervencion = $event->getData();

            $form = $event->getForm();

            // chequeo si la intervencion es un cierre
            if ($intervencion->getAccion() == 1) {
                $ultimaIntervencion = $options['ultima_intervencion'];

                //Obtengo el equipo al cual se le realizó la última intervención.
                $equipoUltimaIntervencion = $ultimaIntervencion->getEquipo();

                $form->add('equipo', EntityType::class, array(
                    'class' => Equipo::class,
                    'data' => $equipoUltimaIntervencion
                ))
                    ->add('equipoDesc', TextType::class, array(
                        'label'    => 'Equipo',
                        'disabled' => true,
                        'mapped'   => false,
                        'required' => true,
                        'data'     => $equipoUltimaIntervencion->getNombreCompleto()
                    ));
            } else {
                $form->add('equipo', EntityType::class, array(
                    'class'    => Equipo::class,
                    'required' => true,
                    'placeholder' => 'Elija un equipo',
                    'choices'  => $options['equipos_elegibles'],
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
            'equipos_elegibles' => null
        ]);
    }

    public function getParent()
    {
        return IntervencionType::class;
    }
}
