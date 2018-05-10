<?php


namespace AppBundle\Services;

use AppBundle\Entity\Equipo;
use AppBundle\Entity\Persona;
use AppBundle\Entity\Intervencion;
use AppBundle\Entity\EstadisticaFinal;
use AppBundle\Services\TransporteManager;
use AppBundle\Services\IntervencionesManager;
use Doctrine\ORM\EntityManager;

class EstadisticaManager
{
    private $em;
    private $transporteManager;
    private $intervencionManager;

    public function __construct(EntityManager $em, TransporteManager $tm, IntervencionesManager $im)
    {
        $this->em = $em;
        $this->transporteManager = $tm;
        $this->intervencionManager = $im;
    }

    public function getDistribucionOperacionesPorEquipo(Persona $persona, $desde, $hasta)
    {
        $equipos = $persona->getEquiposActivos();

        $distribucionPorEquipoQb = $this->em
            ->getRepository('AppBundle:EstadisticaFinal')
            ->getDistribucionOperacionesPorEquipo($equipos, $desde, $hasta);

        $distribucionPorEquipo = $distribucionPorEquipoQb->getQuery()->getResult();

        $total = 0;

        $data =  array();

        if ($distribucionPorEquipo) {
            foreach ($distribucionPorEquipo as $distribucion) {
                $total = $total + $distribucion['cant'];
            }


            foreach ($distribucionPorEquipo as $distribucion) {
                $data[] =  array(
                    'name' => $distribucion['acronimo'].' '.$distribucion['nombre'],
                    'y'    =>($distribucion['cant'] / $total),
                    'distribucion' => $distribucion['cant']
                );
            }
        }

        return $data;
    }

    public function getDistribucionOperacionesPorYacimiento(Persona $persona, $desde, $hasta)
    {
        $equipos = $persona->getEquiposActivos();

        $distribucionPorYacimientoQb = $this->em
            ->getRepository('AppBundle:EstadisticaFinal')
            ->getDistribucionOperacionesPorYacimiento($equipos, $desde, $hasta);

        $distribucionPorYacimiento = $distribucionPorYacimientoQb->getQuery()->getResult();

        $total = 0;

        $data =  array();

        if ($distribucionPorYacimiento) {
            foreach ($distribucionPorYacimiento as $distribucion) {
                $total = $total + $distribucion['cant'];
            }


            foreach ($distribucionPorYacimiento as $distribucion) {
                $data[] = array(
                    'name' => $distribucion['nombre'],
                    'y' => ($distribucion['cant'] / $total),
                    'distribucion' => $distribucion['cant']
                );
            }
        }

        return $data;
    }


    public function getPromediosCanoHora(Persona $persona, $desde, $hasta)
    {
        $equipos = $persona->getEquiposActivos();

        $promediosCanosHoraQb = $this->em
            ->getRepository('AppBundle:EstadisticaFinal')
            ->getPromediosCanosHora($equipos, $desde, $hasta);

        $promediosCanosHora = $promediosCanosHoraQb->getQuery()->getResult();

        $data =  array();

        if ($promediosCanosHora) {
            foreach ($promediosCanosHora as $promedio) {
                $data[] = array(
                    'name' => $promedio['acronimo'] . ' ' . $promedio['nombre'],
                    'y'    => floatval($promedio['promTbg'])
                );
            }
        }

        return $data;
    }

    public function getPromediosVarillasHora(Persona $persona, $desde, $hasta)
    {
        $equipos = $persona->getEquiposActivos();

        $promediosVarillasHoraQb = $this->em
            ->getRepository('AppBundle:EstadisticaFinal')
            ->getPromedioVarillasHora($equipos, $desde, $hasta);

        $promediosVarillasHora = $promediosVarillasHoraQb->getQuery()->getResult();

        $data =  array();

        if ($promediosVarillasHora) {
            foreach ($promediosVarillasHora as $promedio) {
                $data[] = array(
                    'name' => $promedio['acronimo'] . ' ' . $promedio['nombre'],
                    'y'    => floatval($promedio['promVb'])
                );
            }
        }

        return $data;
    }

    public function getFactorTiempoUtil(Persona $persona, $desde, $hasta)
    {
        $equipos = $persona->getEquiposActivos();

        $factorTiempoUtilQb = $this->em
            ->getRepository('AppBundle:EstadisticaFinal')
            ->getFactorTiempoUtil($equipos, $desde, $hasta);

        $factorTiempoUtil = $factorTiempoUtilQb->getQuery()->getResult();

        $data =  array();

        if ($factorTiempoUtil) {
            foreach ($factorTiempoUtil as $promedio) {
                $data[] = array(
                    'name' => $promedio['acronimo'] . ' ' . $promedio['nombre'],
                    'y'    => floatval($promedio['ftu'])
                );
            }
        }

        return $data;
    }

    public function getDistribucionOperacionesIndividualesPorYacimiento(Equipo $equipo, $desde, $hasta)
    {
        $distribucionPorYacimientoQb = $this->em
            ->getRepository('AppBundle:EstadisticaFinal')
            ->getDistribucionOperacionesIndividualesPorYacimiento($equipo, $desde, $hasta);

        $distribucionPorYacimiento = $distribucionPorYacimientoQb->getQuery()->getResult();

        $total = 0;

        $data =  array();

        if ($distribucionPorYacimiento) {
            foreach ($distribucionPorYacimiento as $distribucion) {
                $total = $total + $distribucion['cant'];
            }


            foreach ($distribucionPorYacimiento as $distribucion) {
                $data[] = array(
                    'name' => $distribucion['nombre'],
                    'y' => ($distribucion['cant'] / $total),
                    'distribucion' => $distribucion['cant']
                );
            }
        }

        return $data;
    }

    public function getPromediosIndividualesCanoHora(Equipo $equipo, $desde, $hasta)
    {
        $promediosCanosHoraQb = $this->em
            ->getRepository('AppBundle:EstadisticaFinal')
            ->getPromediosIndividualesCanosHora($equipo, $desde, $hasta);

        $promediosCanosHora = $promediosCanosHoraQb->getQuery()->getResult();

        $data =  array();

        if ($promediosCanosHora) {
            foreach ($promediosCanosHora as $promedio) {
                $intervencionCierreQb = $this->em->getRepository(Intervencion::class)
                  ->getIntervencionCierreByEquipoYFechaApertura($equipo, $promedio['fecha']);

                $intervencionCierre = $intervencionCierreQb->getQuery()->getOneOrNullResult();

                if ($intervencionCierre) {
                    $fhasta = $intervencionCierre->getFecha()->format('U') * 1000;
                } else {
                    $fechaActual = new \DateTime();
                    $fhasta = $fechaActual->format('U') * 1000;
                }

                $data[] = array(
                    'x' => $promedio['fecha']->getTimestamp()*1000,
                    'desc' => $promedio['fecha']->format('d-m-Y H:i:s') . ' ' . $promedio['acronimo'],
                    'fintervencion' => $promedio['fecha']->format('YmdHi'),
                    'fdesde' => intval($promedio['fecha']->format('U')) * 1000,
                    'fhasta' => $fhasta,
                    'y'    => floatval($promedio['promTbg'])
                );
            }
        }

        return $data;
    }

    public function getPromediosIndividualesVarillasHora(Equipo $equipo, $desde, $hasta)
    {
        $promediosCanosHoraQb = $this->em
            ->getRepository('AppBundle:EstadisticaFinal')
            ->getPromediosIndividualesVarillasHora($equipo, $desde, $hasta);

        $promediosCanosHora = $promediosCanosHoraQb->getQuery()->getResult();

        $data =  array();

        if ($promediosCanosHora) {
            foreach ($promediosCanosHora as $promedio) {
                $intervencionCierreQb = $this->em->getRepository(Intervencion::class)
                  ->getIntervencionCierreByEquipoYFechaApertura($equipo, $promedio['fecha']);

                $intervencionCierre = $intervencionCierreQb->getQuery()->getOneOrNullResult();

                if ($intervencionCierre) {
                    $fhasta = $intervencionCierre->getFecha()->format('U') * 1000;
                } else {
                    $fechaActual = new \DateTime();
                    $fhasta = $fechaActual->format('U') * 1000;
                }

                $data[] = array(
                    'x' => $promedio['fecha']->getTimestamp()*1000,
                    'desc' => $promedio['fecha']->format('d-m-Y H:i:s') . ' ' . $promedio['acronimo'],
                    'fintervencion' => $promedio['fecha']->format('YmdHi'),
                    'fdesde' => intval($promedio['fecha']->format('U')) * 1000,
                    'fhasta' => $fhasta,
                    'y'    => floatval($promedio['promVb'])
                );
            }
        }

        return $data;
    }


    public function getIndividualesFactorTiempoUtil(Equipo $equipo, $desde, $hasta)
    {
        $promediosCanosHoraQb = $this->em
            ->getRepository('AppBundle:EstadisticaFinal')
            ->getIndividualesFactorTiempoUtil($equipo, $desde, $hasta);

        $promediosCanosHora = $promediosCanosHoraQb->getQuery()->getResult();

        $data =  array();

        if ($promediosCanosHora) {
            foreach ($promediosCanosHora as $promedio) {
                $intervencionCierreQb = $this->em->getRepository(Intervencion::class)
                  ->getIntervencionCierreByEquipoYFechaApertura($equipo, $promedio['fecha']);

                $intervencionCierre = $intervencionCierreQb->getQuery()->getOneOrNullResult();

                if ($intervencionCierre) {
                    $fhasta = $intervencionCierre->getFecha()->format('U') * 1000;
                } else {
                    $fechaActual = new \DateTime();
                    $fhasta = $fechaActual->format('U') * 1000;
                }

                $data[] = array(
                    'y'             => floatval($promedio['ftu']),
                    'x' => $promedio['fecha']->getTimestamp()*1000,
                    'desc'          => $promedio['fecha']->format('d-m-Y H:i:s') . ' ' . $promedio['acronimo'],
                    'fintervencion' => $promedio['fecha']->format('YmdHi'),
                    'fdesde' => intval($promedio['fecha']->format('U')) * 1000,
                    'fhasta' => $fhasta
                );
            }
        }

        return $data;
    }

    public function getByIntervencion(Intervencion $intervencion)
    {
        $estadisticaFinal = $this->em->getRepository(EstadisticaFinal::class)->findOneBy(['intervencion'=>$intervencion]);

        return $estadisticaFinal;
    }


    public function getTiemposTransporte(Intervencion $intervencion)
    {
        $intervencionAnterior = $this->intervencionManager->getIntervencionAnterior($intervencion);

        $fdesde = null;

        if ($intervencionAnterior) {
            $fdesde = $intervencionAnterior->getFecha();
        }

        $equipo = $intervencion->getEquipo();

        $fhasta = $intervencion->getFecha();

        $transportes = $this->transporteManager->getTransportesByEquipo($equipo, ['inicio'=>'ASC'], $fdesde, $fhasta);

        $kmsRecorridos = 0;
        $tiempoTotal = 0;
        $inicioTransporte = null;
        $finTransporte    = null;
        $tiemposDetenidos = [];
        $tiempoTotalDetenido = new \DateTime('00:00');
        $zeroTiempoTotalDetenido = clone $tiempoTotalDetenido;

        $index = 0;
        $transporteAnterior = null;

        foreach ($transportes as $transporte) {
            $kmsRecorridos += $transporte->getKmsRecorridos();
            $tiempoTotal   += $transporte->getTiempoTotal();

            // si no es la primera iteracion
            if ($index) {
                // si se detuvo el transporte
                if ($transporteAnterior->getFin() !== $transporte->getInicio()) {
                    $duracion = $transporteAnterior->getFin()->diff($transporte->getInicio());

                    $tiemposDetenidos[] = [
                      'inicio' => $transporteAnterior->getFin(),
                      'fin' => $transporte->getInicio(),
                      'duracion' => $duracion
                    ];

                    $tiempoTotalDetenido->add($duracion);
                }

                // es la última iteración, por ende el último transporte
                if ($index + 1 == count($transportes)) {
                    // fin del transporte
                    $finTransporte = $transporte->getFin();
                }
            } else {
                // inicio del transporte
                $inicioTransporte = $transporte->getInicio();
            }

            $transporteAnterior = $transporte;

            $index++;
        }

        return [
          'inicio'              => $inicioTransporte,
          'fin'                 => $finTransporte,
          'tiempoTotal'         => $this->convertirAHorasMinutos($tiempoTotal),
          'kmsRecorridos'       => $kmsRecorridos,
          'tiemposDetenidos'    => $tiemposDetenidos,
          'tiempoTotalDetenido' => $zeroTiempoTotalDetenido->diff($tiempoTotalDetenido),
          'transportes'         => $transportes
        ];
    }

    private function convertirAHorasMinutos($time, $format = 'H:i')
    {
        $aTime = explode('.', $time);

        return date($format, mktime($aTime[0], $aTime[1]));
    }
}
