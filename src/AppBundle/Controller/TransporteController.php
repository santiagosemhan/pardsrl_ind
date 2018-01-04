<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Equipo;
use AppBundle\Entity\Transporte;

class TransporteController extends Controller
{
    public function indexByEquipoAction(Request $request, Equipo $equipo)
    {
        $transporteRepository = $this->getDoctrine()->getRepository(Transporte::class);

        $transportes = $transporteRepository->getTransportesByEquipo($equipo);

        $paginator = $this->get('knp_paginator');

        $transportesPag = $paginator->paginate(
            $transportes,
            $request->query->get('page', 1)/* page number */,
            10/* limit per page */
        );

        return $this->render('AppBundle:Transporte:index_by_equipo.html.twig', [
          'transportes' => $transportesPag,
          'equipo' => $equipo
        ]);
    }

    public function reporteDTMAction(Transporte $transporte)
    {
        return $this->render(':reports:reporte_dtm.html.twig', [
            'transporte' => $transporte,
            'coordinates' => json_encode($transporte->getRecorrido())
        ]);
    }
}
