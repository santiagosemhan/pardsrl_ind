<?php


namespace AppBundle\Services;

use Doctrine\ORM\EntityManager;
use AppBundle\Entity\Transporte;

class TransporteManager
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getTransportesByEquipo($equipoId, $orderBy, $desde = null, $hasta = null)
    {
        return $this->em->getRepository(Transporte::class)->getTransportesByEquipo($equipoId, $orderBy, $desde, $hasta)->getQuery()->getResult();
    }
}
