<?php

namespace UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LogController extends Controller
{
    public function authenticationLogAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $logRep = $em->getRepository('UsuarioBundle:LogAuthentication');

        $paginator = $this->get('knp_paginator');

        $logs = $paginator->paginate(
            $logRep->getOrderByFecha(),
            $request->query->get('page', 1)/* page number */,
            25/* limit per page */
        );

        return $this->render('UsuarioBundle:log:authentication_log.html.twig', [
            'logs' => $logs
        ]);
    }

    public function AccesoDenegadoLogAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $logRep = $em->getRepository('UsuarioBundle:LogAccesoDenegado');

        $paginator = $this->get('knp_paginator');

        $logs = $paginator->paginate(
          $logRep->getOrderByFecha(),
          $request->query->get('page', 1)/* page number */,
          25/* limit per page */
        );


        return $this->render('UsuarioBundle:log:acceso_denegado_log.html.twig', [
            'logs' => $logs
        ]);
    }
}
