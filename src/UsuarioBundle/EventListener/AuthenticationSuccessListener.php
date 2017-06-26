<?php
namespace UsuarioBundle\EventListener;

use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Doctrine\ORM\EntityManager;
use UsuarioBundle\Entity\LogAuthentication;

class AuthenticationSuccessListener
{
    private $em;

    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $token = $event->getAuthenticationToken();
        $request = $event->getRequest();
        $this->onAuthenticationSuccess($request, $token);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        $logAuth = new LogAuthentication();

        $logAuth->setUsername($token->getUsername());

        $logAuth->setIp($request->getClientIp());

        $logAuth->setFecha(new \DateTime('NOW'));

        $logAuth->setUserAgent($request->headers->get('User-Agent'));

        $logAuth->setUsuario($token->getUser());

        $this->em->persist($logAuth);

        $this->em->flush();
    }
}
