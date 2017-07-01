<?php

namespace UsuarioBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\Routing\Route;

/**
* Se lanza el evento acceso.denegado cada vez que un usuario intenta ingresar a
* un lugar no permitido del sistema
*
*/
class AccesoDenegadoEvent extends Event
{
    const NAME = 'acceso.denegado';

    protected $route;

    protected $routeName;

    public function __construct(string $route, string $routeName)
    {
        $this->route = $route;

        $this->routeName = $routeName;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getRouteName()
    {
        return $this->routeName;
    }
}
