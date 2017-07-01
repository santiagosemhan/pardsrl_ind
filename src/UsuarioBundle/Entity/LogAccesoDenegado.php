<?php

namespace UsuarioBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LogAccesoDenegado
 *
 * @ORM\Table(name="log_acceso_denegado")
 * @ORM\Entity(repositoryClass="UsuarioBundle\Repository\LogAccesoDenegadoRepository")
 */
class LogAccesoDenegado
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
     * @ORM\Column(name="username", type="string", length=50)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="route", type="string", length=100)
     */
    private $route;

    /**
     * @var string
     *
     * @ORM\Column(name="route_name", type="string", length=100)
     */
    private $routeName;
    /**
     *
     * @var string
     *
     * @ORM\Column(name="extra_info", type="json_array")
     */
    private $extraInfo = [];

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="datetime")
     */
    private $fecha;

    /**
     * @var
     *
     * @ORM\ManyToOne(targetEntity="UsuarioBundle\Entity\Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id",nullable=false)
     */
    private $usuario;

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
     * Set username
     *
     * @param string $username
     *
     * @return LogAcessoDenegado
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set route
     *
     * @param string $route
     *
     * @return LogAccesoDenegado
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * Set routeName
     *
     * @param string $routeName
     *
     * @return LogAccesoDenegado
     */
    public function setRouteName($routeName)
    {
        $this->routeName = $routeName;

        return $this;
    }

    /**
     * Get routeName
     *
     * @return string
     */
    public function getRouteName()
    {
        return $this->routeName;
    }

    /**
     * Set extraInfo
     *
     * @param array $extraInfo
     *
     * @return LogAccesoDenegado
     */
    public function setExtraInfo($extraInfo)
    {
        $this->extraInfo = $extraInfo;

        return $this;
    }

    /**
     * Get extraInfo
     *
     * @return array
     */
    public function getExtraInfo()
    {
        return $this->extraInfo;
    }

    /**
     * Set fecha
     *
     * @param \DateTime $fecha
     *
     * @return LogAccesoDenegado
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set usuario
     *
     * @param \UsuarioBundle\Entity\Usuario $usuario
     *
     * @return LogAccesoDenegado
     */
    public function setUsuario(\UsuarioBundle\Entity\Usuario $usuario)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \UsuarioBundle\Entity\Usuario
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
