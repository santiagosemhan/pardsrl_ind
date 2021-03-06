<?php

namespace UsuarioBundle\Repository;

use UsuarioBundle\Entity\Rol;
use UsuarioBundle\Entity\Funcionalidad;

/**
 * FuncionalidadRolRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FuncionalidadRolRepository extends \Doctrine\ORM\EntityRepository
{
    public function getQb()
    {
        return $this->createQueryBuilder('fr');
    }

    public function getByFuncionalidadYRol(Funcionalidad $funcionalidad, Rol $rol)
    {
        return $this->getQb()
            ->where('fr.funcionalidad = :funcionalidad')
            ->andWhere('fr.rol = :rol')
            ->setParameter('funcionalidad', $funcionalidad)
            ->setParameter('rol', $rol);
    }
}
