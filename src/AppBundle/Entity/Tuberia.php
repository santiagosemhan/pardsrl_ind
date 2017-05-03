<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Base\BaseClass;

/**
 * Tuberia
 *
 * @ORM\Table(name="tuberia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TuberiaRepository")
 */
class Tuberia extends BaseClass
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
     * @var string
     *
     * @ORM\Column(name="marca", type="string", length=255, nullable=false)
     */
    private $marca;

    /**
     * @var string
     *
     * @ORM\Column(name="modelo", type="string", length=255, nullable=false)
     */
    private $modelo;

    /**
     * @var string
     *
     * @ORM\Column(name="longitud", type="string", length=10, nullable=false)
     */
    private $longitud;

    /**
     * @var string
     *
     * @ORM\Column(name="diametro", type="string", length=10, nullable=false)
     */
    private $diametro;

    /**
     * @var string
     *
     * @ORM\Column(name="espesor", type="string", length=10, nullable=false)
     */
    private $espesor;

    /**
     * @var string
     *
     * @ORM\Column(name="presion_maxima", type="string", length=10, nullable=false)
     */
    private $presionMaxima;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipo")
     * @ORM\JoinColumn(name="equipo_id", referencedColumnName="id")
     */
    private $equipo;


    /**
     * @var string
     *
     * @ORM\Column(name="nro_serie", type="string", length=25, nullable=false)
     */
    private $nroSerie;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set marca
     *
     * @param string $marca
     *
     * @return Tuberia
     */
    public function setMarca($marca)
    {
        $this->marca = $marca;

        return $this;
    }

    /**
     * Get marca
     *
     * @return string
     */
    public function getMarca()
    {
        return $this->marca;
    }

    /**
     * Set modelo
     *
     * @param string $modelo
     *
     * @return Tuberia
     */
    public function setModelo($modelo)
    {
        $this->modelo = $modelo;

        return $this;
    }

    /**
     * Get modelo
     *
     * @return string
     */
    public function getModelo()
    {
        return $this->modelo;
    }

    /**
     * Set longitud
     *
     * @param string $longitud
     *
     * @return Tuberia
     */
    public function setLongitud($longitud)
    {
        $this->longitud = $longitud;

        return $this;
    }

    /**
     * Get longitud
     *
     * @return string
     */
    public function getLongitud()
    {
        return $this->longitud;
    }

    /**
     * Set diametro
     *
     * @param string $diametro
     *
     * @return Tuberia
     */
    public function setDiametro($diametro)
    {
        $this->diametro = $diametro;

        return $this;
    }

    /**
     * Get diametro
     *
     * @return string
     */
    public function getDiametro()
    {
        return $this->diametro;
    }

    /**
     * Set espesor
     *
     * @param string $espesor
     *
     * @return Tuberia
     */
    public function setEspesor($espesor)
    {
        $this->espesor = $espesor;

        return $this;
    }

    /**
     * Get espesor
     *
     * @return string
     */
    public function getEspesor()
    {
        return $this->espesor;
    }

    /**
     * Set presionMaxima
     *
     * @param string $presionMaxima
     *
     * @return Tuberia
     */
    public function setPresionMaxima($presionMaxima)
    {
        $this->presionMaxima = $presionMaxima;

        return $this;
    }

    /**
     * Get presionMaxima
     *
     * @return string
     */
    public function getPresionMaxima()
    {
        return $this->presionMaxima;
    }

    /**
     * Set equipo
     *
     * @param \AppBundle\Entity\Equipo $equipo
     *
     * @return Tuberia
     */
    public function setEquipo(\AppBundle\Entity\Equipo $equipo = null)
    {
        $this->equipo = $equipo;

        return $this;
    }

    /**
     * Get equipo
     *
     * @return \AppBundle\Entity\Equipo
     */
    public function getEquipo()
    {
        return $this->equipo;
    }

    /**
     * Set nroSerie
     *
     * @param string $nroSerie
     *
     * @return Tuberia
     */
    public function setNroSerie($nroSerie)
    {
        $this->nroSerie = $nroSerie;

        return $this;
    }

    /**
     * Get nroSerie
     *
     * @return string
     */
    public function getNroSerie()
    {
        return $this->nroSerie;
    }

    /**
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Tuberia
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Tuberia
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Set creadoPor
     *
     * @param \UsuarioBundle\Entity\Usuario $creadoPor
     *
     * @return Tuberia
     */
    public function setCreadoPor(\UsuarioBundle\Entity\Usuario $creadoPor = null)
    {
        $this->creadoPor = $creadoPor;

        return $this;
    }

    /**
     * Set actualizadoPor
     *
     * @param \UsuarioBundle\Entity\Usuario $actualizadoPor
     *
     * @return Tuberia
     */
    public function setActualizadoPor(\UsuarioBundle\Entity\Usuario $actualizadoPor = null)
    {
        $this->actualizadoPor = $actualizadoPor;

        return $this;
    }
}
