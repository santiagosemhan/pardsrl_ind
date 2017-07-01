<?php

namespace UsuarioBundle\EventListener;

use UsuarioBundle\Event\AccesoDenegadoEvent;
use UsuarioBundle\Entity\LogAccesoDenegado;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class AccesoDenegadoListener
{
    protected $em;
    protected $token;

    public function __construct(EntityManager $em, TokenStorage $token)
    {
        $this->em = $em;
        $this->token = $token->getToken();
    }

    public function onAccesoDenegado(AccesoDenegadoEvent $event)
    {
        $route = $event->getRoute();

        $routeName = $event->getRouteName();

        $log = new LogAccesoDenegado();

        $log->setUsername($this->token->getUsername());

        $log->setUsuario($this->token->getUser());

        $roles = $this->token->getUser()->getRoles();

        $aExtraInfo = ['roles' => implode(',', $roles)];

        $log->setExtraInfo($aExtraInfo);

        $log->setRoute($route);

        $log->setRouteName($routeName);

        $log->setFecha(new \DateTime('NOW'));

        $this->em->persist($log);

        $this->em->flush();
    }
}
