<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\Base\BaseClass;

/**
 * Ensayo
 *
 * @ORM\Table(name="ensayo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EnsayoRepository")
 */
class Ensayo extends BaseClass
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
     * @var string
     *
     * @ORM\Column(name="metodo", type="string", length=255)
     */
    private $metodo;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Tuberia")
     * @ORM\JoinColumn(name="tuberia_id", referencedColumnName="id")
     */
    private $tuberia;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Instrumento")
     * @ORM\JoinColumn(name="instrumento_id", referencedColumnName="id")
     */
    private $instrumento;
    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set inicio
     *
     * @param \DateTime $inicio
     *
     * @return Ensayo
     */
    public function setInicio($inicio)
    {
        $this->inicio = $inicio;

        return $this;
    }

    /**
     * Get inicio
     *
     * @return \DateTime
     */
    public function getInicio()
    {
        return $this->inicio;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     *
     * @return Ensayo
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set tuberia
     *
     * @param \AppBundle\Entity\Tuberia $tuberia
     *
     * @return Ensayo
     */
    public function setTuberia(\AppBundle\Entity\Tuberia $tuberia = null)
    {
        $this->tuberia = $tuberia;

        return $this;
    }

    /**
     * Get tuberia
     *
     * @return \AppBundle\Entity\Tuberia
     */
    public function getTuberia()
    {
        return $this->tuberia;
    }

    /**
     * Set metodo
     *
     * @param string $metodo
     *
     * @return Ensayo
     */
    public function setMetodo($metodo)
    {
        $this->metodo = $metodo;

        return $this;
    }

    /**
     * Get metodo
     *
     * @return string
     */
    public function getMetodo()
    {
        return $this->metodo;
    }

    /**
     * Set instrumento
     *
     * @param \AppBundle\Entity\Instrumento $instrumento
     *
     * @return Ensayo
     */
    public function setInstrumento(\AppBundle\Entity\Instrumento $instrumento = null)
    {
        $this->instrumento = $instrumento;

        return $this;
    }

    /**
     * Get instrumento
     *
     * @return \AppBundle\Entity\Instrumento
     */
    public function getInstrumento()
    {
        return $this->instrumento;
    }
}
