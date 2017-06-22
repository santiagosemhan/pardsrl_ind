<?php

namespace UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UsuarioBundle\Entity\FuncionalidadRol;

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

                $aFuncionalidadesAcciones[$funcionalidadKey][$accionKey] = $funcionalidad->getAcciones()->contains($accion) ? 1 : 0;
            }
        }


        return $this->render('UsuarioBundle:seguridad:editor_avanzado.html.twig', [
            'roles'           => $roles,
            'funcionalidades' => $funcionalidades,
            'acciones'        => $acciones,
            'funcionalidadesRolesMatriz' => $aFuncionalidadesRoles,
            'funcionalidadesAccionesMatriz' => $aFuncionalidadesAcciones
        ]);
    }


    public function desactivarFuncionalidadRolAction(Request $request)
    {
        $success = false;

        try {
            $em = $this->getDoctrine()->getManager();

            $funcionalidad = $em->find('UsuarioBundle:Funcionalidad', $request->get('funcionalidad'));

            $rol = $em->find('UsuarioBundle:Rol', $request->get('rol'));

            $success = $this->get('security.manager')->desactivarFuncionalidadRol($funcionalidad, $rol);

            $message = "La relación $funcionalidad / $rol se ha desactivado correctamente";
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return $this->json([
          'success' => $success,
          'message' => $message
        ]);
    }

    public function activarFuncionalidadRolAction(Request $request)
    {
        $success = false;

        try {
            $em = $this->getDoctrine()->getManager();

            $funcionalidad = $em->find('UsuarioBundle:Funcionalidad', $request->get('funcionalidad'));

            $rol = $em->find('UsuarioBundle:Rol', $request->get('rol'));

            $success = $this->get('security.manager')->activarFuncionalidadRol($funcionalidad, $rol);

            $message = "La relación $funcionalidad / $rol se ha activado correctamente";
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return $this->json([
        'success' => $success,
        'message' => $message
      ]);
    }

    public function desactivarFuncionalidadAccionAction(Request $request)
    {
        $success = false;

        try {
            $em = $this->getDoctrine()->getManager();

            $funcionalidad = $em->find('UsuarioBundle:Funcionalidad', $request->get('funcionalidad'));

            $accion = $em->find('UsuarioBundle:Accion', $request->get('accion'));

            $success = $this->get('security.manager')->desactivarFuncionalidadAccion($funcionalidad, $accion);

            $message = "La relación $funcionalidad / $accion se ha desactivado correctamente";
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return $this->json([
          'success' => $success,
          'message' => $message
        ]);
    }

    public function activarFuncionalidadAccionAction(Request $request)
    {
        $success = false;

        try {
            $em = $this->getDoctrine()->getManager();

            $funcionalidad = $em->find('UsuarioBundle:Funcionalidad', $request->get('funcionalidad'));

            $accion = $em->find('UsuarioBundle:Accion', $request->get('accion'));

            $success = $this->get('security.manager')->activarFuncionalidadAccion($funcionalidad, $accion);

            $message = "La relación $funcionalidad / $accion se ha activado correctamente";
        } catch (\Exception $e) {
            $message = $e->getMessage();
        }

        return $this->json([
        'success' => $success,
        'message' => $message
      ]);
    }
}
