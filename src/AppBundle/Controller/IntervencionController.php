<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Intervencion;
use AppBundle\Entity\Pozo;
use AppBundle\Entity\Equipo;
use AppBundle\Form\IntervencionType;
use AppBundle\Form\IntervencionPozoType;
use AppBundle\Form\IntervencionEquipoType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;

class IntervencionController extends Controller
{
    /**
     *
     * Ejecuta un listado de intervenciones filtradas por pozo, más un formulario de nueva intervención.
     *
     * @param Request $request
     * @param Pozo $pozo
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request, Pozo $pozo)
    {
        $intervencionRep = $this->getDoctrine()->getRepository(Intervencion::class);

        $ultimaIntervencion = $intervencionRep->getUltimaIntervencionByPozo($pozo)->getQuery()->getOneOrNullResult();

        $intervencion = $this->inicializarIntervencion($ultimaIntervencion, $pozo);

        $equiposActivos = $this->getDoctrine()->getRepository(Equipo::class)->findBy(array('activo' => true));

        $equiposElegibles = new ArrayCollection();

        foreach ($equiposActivos as $equipo) {
            if (!$equipo->estaInterviniendo()) {
                $equiposElegibles->add($equipo);
            }
        }

        $form = $this->createForm(IntervencionEquipoType::class, $intervencion, array(
            'action' => $this->generateUrl('intervencion_index', array('id' => $pozo->getId())),
            'method' => 'POST',
            'equipos_elegibles' => $equiposElegibles,
            'ultima_intervencion' => $ultimaIntervencion
        ));

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($intervencion);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                    'La intervención se ha registrado satisfactoriamente.');


            return $this->redirectToRoute('intervencion_index', array('id' => $pozo->getid()));
        }

        $intervenciones = $intervencionRep->getUltimasIntervencionesByPozo($pozo);

        $paginator = $this->get('knp_paginator');

        $intervenciones = $paginator->paginate(
            $intervenciones,
            $request->query->get('page', 1),
            10/* limit per page */
        );

        return $this->render('AppBundle:intervencion:index.html.twig', array(
            'form' => $form->createView(),
            'intervenciones' => $intervenciones,
            'pozo' => $pozo
        ));
    }



    public function intervencionesEquipoAction(Request $request, Equipo $equipo)
    {
        //TODO Refactorizar este controller

        $em = $this->getDoctrine()->getManager();

        $intervencionRepository = $em->getRepository(Intervencion::class);

        $intervencionesQb = $intervencionRepository->getUltimasIntervencionesByEquipo($equipo);

        $ultimaIntervencion = $intervencionRepository->getUltimaIntervencionByEquipo($equipo)->getQuery()->getOneOrNullResult();

        $nuevaIntervencion = $this->inicializarIntervencion($ultimaIntervencion, null, $equipo);

        $pozosActivos = $em->getRepository(Pozo::class)->findBy(array('activo' => true));

        $pozosElegibles = new ArrayCollection();

        foreach ($pozosActivos as $pozo) {
            if (!$pozo->estaAbierto()) {
                $pozosElegibles->add($pozo);
            }
        }

        $form = $this->createForm(IntervencionPozoType::class, $nuevaIntervencion, [
            'pozos_elegibles' => $pozosElegibles,
            'ultima_intervencion' => $ultimaIntervencion,
            'action' => $this->generateUrl('intervencion_equipo_index', array('id' => $equipo->getId())),
            'method' => 'POST'
        ]);


        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($nuevaIntervencion);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'La intervención se ha registrado satisfactoriamente.');

            return $this->redirectToRoute('intervencion_equipo_index', array('id' => $equipo->getid()));
        }


        $paginator = $this->get('knp_paginator');

        $intervenciones = $paginator->paginate(
            $intervencionesQb,
            $request->query->get('page', 1)/* page number */,
            10/* limit per page */
        );

        return $this->render('AppBundle:intervencion:intervenciones_equipo.html.twig', [
          'equipo' => $equipo,
          'form' => $form->createView(),
          'intervenciones' => $intervenciones
        ]);
    }


    /**
     * Inicializa una intervencion para un determinado pozo.
     * Revisa la próxima intervencion a realizar.
     *
     * @param Pozo $pozo
     * @return Intervencion
     */
    private function inicializarIntervencion($ultimaIntervencion = null, Pozo $pozo = null, Equipo $equipo = null)
    {
        $intervencion = new Intervencion();

        $fechaHoy = new \DateTime('NOW');

        $intervencion->setFecha($fechaHoy);

        if ($pozo) {
            $intervencion->setPozo($pozo);
        }

        if ($equipo) {
            $intervencion->setEquipo($equipo);
        }

        //por defecto se considera que la intervencion a realizar es una apertura de pozo
        $intervencion->setAccion(0);

        if ($ultimaIntervencion) {
            $accion = $ultimaIntervencion->getAccion();

            //si la ultima intervencion efectuada fue una apertura, el formulario debe permitir solamente cerrar el pozo.
            if ($accion == 0) {
                $intervencion->setAccion(1);
            }
        }

        return $intervencion;
    }
}
