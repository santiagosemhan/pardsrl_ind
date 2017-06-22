<?php

namespace UsuarioBundle\Services;

use Doctrine\ORM\EntityManager;
use UsuarioBundle\Entity\Funcionalidad;
use UsuarioBundle\Entity\FuncionalidadRol;
use UsuarioBundle\Entity\FuncionalidadAccion;
use UsuarioBundle\Entity\Rol;
use UsuarioBundle\Entity\Accion;

class SecurityManager
{
    const ROLE_SUPER_ADMIN = "ROLE_SUPER_ADMIN";

    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     *
     * Garantiza el acceso del usuario a ciertas acciones definidas en base de datos.
     *
     * @param $role
     * @param $ruta
     * @return bool
     */
    public function isGranted($role, $ruta)
    {
        $rol = $this->em->getRepository('UsuarioBundle:Rol')->findOneBySlug($role);

        //si no encuentra el rol, se deniega el acceso.
        if (!$rol) {
            return false;
        }

        //obtengo las funcionalidades activas
        $funcionalidades = $rol->getFuncionalidades();

        foreach ($funcionalidades as $funcionalidad) {

            //recorro las acciones activas
            foreach ($funcionalidad->getAcciones() as $accion) {
                if ($accion->getRuta() == $ruta) {
                    return true;
                }
            }
        }

        return false;
    }

    public function activarFuncionalidadRol(Funcionalidad $funcionalidad, Rol $rol)
    {
        if (is_null($funcionalidad) || is_null($rol)) {
            throw new \Exception('No se pudo activar la relación funcionalidad/rol: Funcionalidad o Rol no disponible.');
        }

        $funcionalidadRol = $this->em->getRepository('UsuarioBundle:FuncionalidadRol')->getByFuncionalidadYRol($funcionalidad, $rol)->getQuery()->getOneOrNullResult();

        if ($funcionalidadRol) {
            $funcionalidadRol->setActivo(true);
        } else {
            $funcionalidadRol = new FuncionalidadRol();

            $funcionalidadRol->setFuncionalidad($funcionalidad);

            $funcionalidadRol->setRol($rol);

            $funcionalidadRol->setActivo(true);
        }

        $success = true;

        $this->em->persist($funcionalidadRol);

        $this->em->flush();

        return true;
    }

    public function desactivarFuncionalidadRol(Funcionalidad $funcionalidad, Rol $rol)
    {
        if (is_null($funcionalidad) || is_null($rol)) {
            throw new \Exception('No se pudo activar la relación funcionalidad/rol: Funcionalidad o Rol no disponible');
        }

        if ($rol->getSlug() == self::ROLE_SUPER_ADMIN) {
            throw new \Exception('No se pueden desactivar funcionalidades para el rol SUPER ADMINISTRADOR');
        }

        $funcionalidadRol = $this->em->getRepository('UsuarioBundle:FuncionalidadRol')->getByFuncionalidadYRol($funcionalidad, $rol)->getQuery()->getOneOrNullResult();

        if ($funcionalidadRol) {
            $funcionalidadRol->setActivo(false);

            $success = true;

            $this->em->persist($funcionalidadRol);
            $this->em->flush();
        } else {
            throw new \Exception('No es posible desactivar una relación Funcionalidad/Rol No identificada');
        }

        return true;
    }

    public function activarFuncionalidadAccion(Funcionalidad $funcionalidad, Accion $accion)
    {
        if (is_null($funcionalidad) || is_null($accion)) {
            throw new \Exception('Funcionalidad o Acción no disponible (vacío)');
        }

        $funcionalidadAccion = $this->em->getRepository('UsuarioBundle:FuncionalidadAccion')->getByFuncionalidadYAccion($funcionalidad, $accion)->getQuery()->getOneOrNullResult();

        if ($funcionalidadAccion) {
            $funcionalidadAccion->setActivo(true);
        } else {
            $funcionalidadAccion = new FuncionalidadAccion();

            $funcionalidadAccion->setFuncionalidad($funcionalidad);

            $funcionalidadAccion->setAccion($accion);

            $funcionalidadAccion->setActivo(true);
        }

        $success = true;

        $this->em->persist($funcionalidadAccion);

        $this->em->flush();

        return true;
    }

    public function desactivarFuncionalidadAccion(Funcionalidad $funcionalidad, Accion $accion)
    {
        if (is_null($funcionalidad) || is_null($accion)) {
            throw new \Exception('Funcionalidad o Acción no disponible (vacío)');
        }

        $funcionalidadAccion = $this->em->getRepository('UsuarioBundle:FuncionalidadAccion')->getByFuncionalidadYAccion($funcionalidad, $accion)->getQuery()->getOneOrNullResult();

        if ($funcionalidadAccion) {
            $funcionalidadAccion->setActivo(false);

            $success = true;

            $this->em->persist($funcionalidadAccion);
            $this->em->flush();
        } else {
            throw new \Exception('No es posible desactivar una relación Funcionalidad/Acción No identificada');
        }

        return true;
    }
}
