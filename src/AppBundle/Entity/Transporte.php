<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Transporte
 *
 * @ORM\Table(name="transporte")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TransporteRepository")
 */
class Transporte
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="inicio", type="datetime")
     */
    private $inicio;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin", type="datetime")
     */
    private $fin;

    /**
     * @var float
     *
     * @ORM\Column(name="kms_recorridos", type="float")
     */
    private $kmsRecorridos;

    /**
     * @var float
     *
     * @ORM\Column(name="velocidad_maxima", type="float")
     */
    private $velocidadMaxima;

    /**
     * @var float
     *
     * @ORM\Column(name="velocidad_promedio", type="float")
     */
    private $velocidadPromedio;

    /**
     * @var float
     *
     * @ORM\Column(name="tiempo_total", type="float")
     */
    private $tiempoTotal;

    /**
     * @var array
     *
     * @ORM\Column(name="recorrido", type="json")
     */
    private $recorrido;


    /**
     * @var $equipo
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipo", inversedBy="intervenciones")
     * @ORM\JoinColumn(name="equipo_id", referencedColumnName="id", nullable=false)
     */
    private $equipo;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set inicio.
     *
     * @param \DateTime $inicio
     *
     * @return Transporte
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio.
     *
     * @return \DateTime
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fin.
     *
     * @param \DateTime $fin
     *
     * @return Transporte
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin.
     *
     * @return \DateTime
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set kmsRecorridos.
     *
     * @param float $kmsRecorridos
     *
     * @return Transporte
     */
    public function setKmsRecorridos($kmsRecorridos)
    {
        $this->kmsRecorridos = $kmsRecorridos;

        return $this;
    }

    /**
     * Get kmsRecorridos.
     *
     * @return float
     */
    public function getKmsRecorridos()
    {
        return $this->kmsRecorridos;
    }

    /**
     * Set velocidadMaxima.
     *
     * @param float $velocidadMaxima
     *
     * @return Transporte
     */
    public function setVelocidadMaxima($velocidadMaxima)
    {
        $this->velocidadMaxima = $velocidadMaxima;

        return $this;
    }

    /**
     * Get velocidadMaxima.
     *
     * @return float
     */
    public function getVelocidadMaxima()
    {
        return $this->velocidadMaxima;
    }

    /**
     * Set velocidadPromedio.
     *
     * @param float $velocidadPromedio
     *
     * @return Transporte
     */
    public function setVelocidadPromedio($velocidadPromedio)
    {
        $this->velocidadPromedio = $velocidadPromedio;

        return $this;
    }

    /**
     * Get velocidadPromedio.
     *
     * @return float
     */
    public function getVelocidadPromedio()
    {
        return $this->velocidadPromedio;
    }

    /**
     * Set tiempoTotal.
     *
     * @param float $tiempoTotal
     *
     * @return Transporte
     */
    public function setTiempoTotal($tiempoTotal)
    {
        $this->tiempoTotal = $tiempoTotal;

        return $this;
    }

    /**
     * Get tiempoTotal.
     *
     * @return float
     */
    public function getTiempoTotal()
    {
        return $this->tiempoTotal;
    }

    /**
     * Set recorrido.
     *
     * @param json $recorrido
     *
     * @return Transporte
     */
    public function setRecorrido($recorrido)
    {
        $this->recorrido = $recorrido;

        return $this;
    }

    /**
     * Get recorrido.
     *
     * @return json
     */
    public function getRecorrido()
    {
        return $this->recorrido;
    }

    /**
     * Set equipo.
     *
     * @param \AppBundle\Entity\Equipo $equipo
     *
     * @return Transporte
     */
    public function setEquipo(\AppBundle\Entity\Equipo $equipo)
    {
        $this->equipo = $equipo;

        return $this;
    }

    /**
     * Get equipo.
     *
     * @return \AppBundle\Entity\Equipo
     */
    public function getEquipo()
    {
        return $this->equipo;
    }
}
