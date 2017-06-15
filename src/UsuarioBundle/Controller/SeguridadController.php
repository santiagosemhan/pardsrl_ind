<?php

namespace UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\Query;

class SeguridadController extends Controller
{
    public function editorAvanzadoAction()
    {
        $em = $this->getDoctrine()->getManager();


        $roles = $em->getRepository('UsuarioBundle:Rol')->findAll();

        $funcionalidades = $em->getRepository('UsuarioBundle:Funcionalidad')->findBy([], ['nombre' => 'ASC']);

        $aFuncionalidadesRoles = [];

        foreach ($funcionalidades as $funcionalidad) {
            $funcionalidadKey = $funcionalidad->getId();

            foreach ($roles as $rol) {
                $rolKey = $rol->getId();

                $aFuncionalidadesRoles[$funcionalidadKey][$rolKey] = $funcionalidad->getRoles()->contains($rol) ? 1 : 0;
            }
        }

        $acciones = $em->getRepository('UsuarioBundle:Accion')->findBy([], ['nombre' => 'ASC']);

        $aFuncionalidadesAcciones = [];

        foreach ($funcionalidades as $funcionalidad) {
            $funcionalidadKey = $funcionalidad->getId();

            foreach ($acciones as $accion) {
                $accionKey = $accion->getId();

                $aFuncionalidadesAcciones[$funcionalidadKey][$accionKey] = $funcionalidad->getAcciones(true)->contains($accion) ? 1 : 0;
            }
        }


        return $this->render('UsuarioBundle:Seguridad:editor_avanzado.html.twig', [
            'roles'           => $roles,
            'funcionalidades' => $funcionalidades,
            'acciones'        => $acciones,
            'funcionalidadesRolesMatriz' => $aFuncionalidadesRoles,
            'funcionalidadesAccionesMatriz' => $aFuncionalidadesAcciones
        ]);
    }
}
