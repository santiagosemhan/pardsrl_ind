<?php


namespace AppBundle\Services;

use AppBundle\Entity\Pozo;
use AppBundle\Entity\Equipo;
use AppBundle\Entity\Intervencion;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;

class IntervencionesManager
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    private function getIntervencionRepository()
    {
        return $this->em->getRepository(Intervencion::class);
    }

    public function getUltimasIntervencionesByEquipo($equipoId)
    {
        return  $this->getIntervencionRepository()->getUltimasIntervencionesByEquipo($equipoId);
    }

    public function getUltimasIntervencionesByPozo($pozoId)
    {
        return  $this->getIntervencionRepository()->getUltimasIntervencionesByPozo($pozoId);
    }

    public function getUltimaIntervencionByEquipo($equipoId)
    {
        return $this->getIntervencionRepository()->getUltimaIntervencionByEquipo($equipoId)->getQuery()->getOneOrNullResult();
    }

    public function getUltimaIntervencionByPozo($pozoId)
    {
        return $this->getIntervencionRepository()->getUltimaIntervencionByPozo($pozoId)->getQuery()->getOneOrNullResult();
    }

    public function getIntervencionCierre($equipoId, $fechaApertura)
    {
        return $this->getIntervencionRepository()->getIntervencionCierreByEquipoYFechaApertura($equipoId, $fechaApertura)->getQuery()->getOneOrNullResult();
    }

    public function getPozosElegibles()
    {
        $pozosActivos = $this->em->getRepository(Pozo::class)->findBy(array('activo' => true));

        $pozosElegibles = new ArrayCollection();

        foreach ($pozosActivos as $pozo) {
            if (!$pozo->estaAbierto()) {
                $pozosElegibles->add($pozo);
            }
        }

        return $pozosElegibles;
    }

    public function getEquiposElegibles()
    {
        $equiposActivos = $this->em->getRepository(Equipo::class)->findBy(array('activo' => true));

        $equiposElegibles = new ArrayCollection();

        foreach ($equiposActivos as $equipo) {
            if (!$equipo->estaInterviniendo()) {
                $equiposElegibles->add($equipo);
            }
        }

        return $equiposElegibles;
    }


    /**
     * Inicializa una intervencion para un determinado pozo y/o equipo
     * Revisa la próxima intervencion a realizar.
     *
     * @param int $ultimaIntervencion
     * @param int $pozoId
     * @param int $equipoId
     *
     * @return Intervencion
     */
    public function inicializarIntervencion($ultimaIntervencionId = null, $pozoId = null, $equipoId = null)
    {
        $pozo = $pozoId ? $this->em->getRepository(Pozo::class)->find($pozoId) : null;

        $equipo = $equipoId ? $this->em->getRepository(Equipo::class)->find($equipoId) : null;

        $ultimaIntervencion = $this->em->getRepository(Intervencion::class)->find($ultimaIntervencionId);

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
