<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Intervencion;
use AppBundle\Entity\Pozo;
use AppBundle\Entity\Equipo;
use AppBundle\Form\IntervencionPozoType;
use AppBundle\Form\IntervencionEquipoType;
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
        $em = $this->getDoctrine()->getManager();

        $intervencionesManager = $this->get('manager.intervenciones');

        $ultimaIntervencion = $intervencionesManager->getUltimaIntervencionByPozo($pozo->getId());

        $intervencionId = $ultimaIntervencion ? $ultimaIntervencion->getId() : null;

        $nuevaIntervencion = $intervencionesManager->inicializarIntervencion($intervencionId, $pozo->getId());

        $equiposElegibles = $intervencionesManager->getEquiposElegibles();

        $form = $this->createForm(IntervencionEquipoType::class, $nuevaIntervencion, array(
            'action' => $this->generateUrl('intervencion_index', array('id' => $pozo->getId())),
            'method' => 'POST',
            'equipos_elegibles' => $equiposElegibles,
            'ultima_intervencion' => $ultimaIntervencion
        ));

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($nuevaIntervencion);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success',
                    'La intervención se ha registrado satisfactoriamente.');


            return $this->redirectToRoute('intervencion_index', array('id' => $pozo->getid()));
        }

        $intervenciones = $intervencionesManager->getUltimasIntervencionesByPozo($pozo->getId());

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
        $em = $this->getDoctrine()->getManager();

        $intervencionesManager = $this->get('manager.intervenciones');

        $ultimaIntervencion = $intervencionesManager->getUltimaIntervencionByEquipo($equipo->getId());

        $pozosElegibles = $intervencionesManager->getPozosElegibles();

        $nuevaIntervencion = $intervencionesManager->inicializarIntervencion($ultimaIntervencion->getId(), null, $equipo->getId());

        $form = $this->createForm(IntervencionPozoType::class, $nuevaIntervencion, [
            'pozos_elegibles' => $pozosElegibles,
            'ultima_intervencion' => $ultimaIntervencion,
            'action' => $this->generateUrl('intervencion_equipo_index', array('id' => $equipo->getId())),
            'method' => 'POST'
        ]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($nuevaIntervencion);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'La intervención se ha registrado satisfactoriamente.');

            return $this->redirectToRoute('intervencion_equipo_index', array('id' => $equipo->getid()));
        }


        $paginator = $this->get('knp_paginator');

        $intervencionesQb = $intervencionesManager->getUltimasIntervencionesByEquipo($equipo->getId());

        //filtro solamente aquellas que sean apertura
        $intervenciones = $intervencionesQb->andWhere('interv.accion = 0')->getQuery()->getResult();

        $intervArr = [];

        foreach ($intervenciones as $intervencion) {
            $intervencionCierre = $intervencionesManager->getIntervencionCierre($equipo->getId(), $intervencion->getFecha());

            $intervArr[] = [
              'pozo' => $intervencion->getPozo(),
              'yacimiento' => $intervencion->getPozo()->getYacimiento(),
              'compania' => $intervencion->getPozo()->getYacimiento()->getCompania(),
              'fecha_apertura' => $intervencion->getFecha(),
              'fecha_cierre' =>  $intervencionCierre ? $intervencionCierre->getFecha() : null ,
              'creado' => $intervencion->getFechaCreacion(),
              'creado_por' => $intervencion->getCreadoPor()
            ];
        }

        $intervencionesPag = $paginator->paginate(
            $intervArr,
            $request->query->get('page', 1)/* page number */,
            10/* limit per page */
        );

        return $this->render('AppBundle:intervencion:intervenciones_equipo.html.twig', [
          'equipo' => $equipo,
          'form' => $form->createView(),
          'intervenciones' => $intervencionesPag
        ]);
    }
}
