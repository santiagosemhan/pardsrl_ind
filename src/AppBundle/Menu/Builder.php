<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use UsuarioBundle\Entity\Menu;
use UsuarioBundle\Services\SecurityManager;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem(
            'root',
            array(
                'childrenAttributes' => array(
                    'class' => 'sidebar-menu',
                ),
            )
        );

        $token = $this->container->get('security.token_storage')->getToken();

        //si existe un token de usuario
        if ($token) {
            $this->usuario = $token->getUser();

            $this->securityManager = $this->container->get('security.manager');

            $em = $this->container->get('doctrine')->getManager();

            $misEquipos = $this->usuario->getPersona()->getEquiposActivos();

            $roles = $this->usuario->getRoles();

            $this->rol = $roles[0];


            if ($this->securityManager->isGranted($this->rol,
                    'dashboard') || $this->usuario->hasRole('ROLE_SUPER_ADMIN')
            ) {
                $menu->addChild('Dashboard', array( 'route' => 'dashboard' ))->setExtra('icon', 'fa fa-area-chart');
            }

            if ($this->securityManager->isGranted($this->rol,
                    'reporte_index') || $this->usuario->hasRole('ROLE_SUPER_ADMIN')
            ) {
                $menu->addChild('Reportes', array( 'route' => 'reporte_index' ))->setExtra('icon', 'fa fa-file-text-o');
            }

            //TODO siempre se están generando estas rutas si el usuario tiene asignado equipos

            if ($misEquipos->count()) {
                $menu->addChild('MIS EQUIPOS')->setAttribute('class', 'header');

                foreach ($misEquipos as $equipo) {
                    $menu->addChild(
                        strtoupper($equipo->getNombreCompleto()),
                        array(
                            'childrenAttributes' => array(
                                'class' => 'treeview-menu',
                            ),
                        )
                    )
                         ->setUri('#')
                         ->setExtra('icon', 'fa fa-circle-o text-active-green')
                         ->setAttribute('class', 'treeview');
                    if ($this->securityManager->isGranted($this->rol, 'equipo_graficas') || $this->usuario->hasRole('ROLE_SUPER_ADMIN')) {
                        $menu[ strtoupper($equipo->getNombreCompleto()) ]->addChild(
                            'Gráficas',
                            array( 'route' => 'equipo_graficas', 'routeParameters' => array( 'id' => $equipo->getId() ) )
                        )->setExtra('icon', 'fa fa-bar-chart');
                    }
                    if ($this->securityManager->isGranted($this->rol, 'equipo_instrumentos') || $this->usuario->hasRole('ROLE_SUPER_ADMIN')) {
                        $menu[ strtoupper($equipo->getNombreCompleto()) ]->addChild(
                            'Instrumentos',
                            array( 'route'           => 'equipo_instrumentos',
                                   'routeParameters' => array( 'id' => $equipo->getId() )
                            )
                        )->setExtra('icon', 'fa  fa-cogs');
                    }
                    if ($this->securityManager->isGranted($this->rol, 'equipo_estadisticas') || $this->usuario->hasRole('ROLE_SUPER_ADMIN')) {
                        $menu[ strtoupper($equipo->getNombreCompleto()) ]->addChild(
                            'Estadística Actual',
                            array( 'route'           => 'equipo_estadisticas',
                                   'routeParameters' => array( 'id' => $equipo->getId() )
                            )
                        )->setExtra('icon', 'fa  fa-line-chart');
                    }
                    if ($this->securityManager->isGranted($this->rol, 'equipo_estadisticas_individuales') || $this->usuario->hasRole('ROLE_SUPER_ADMIN')) {
                        $menu[ strtoupper($equipo->getNombreCompleto()) ]->addChild(
                            'Estadísticas Individuales',
                            array( 'route'           => 'equipo_estadisticas_individuales',
                                   'routeParameters' => array( 'id' => $equipo->getId() )
                            ))->setExtra('icon', 'fa  fa-line-chart');
                    }
                    if ($this->securityManager->isGranted($this->rol, 'intervencion_equipo_index') || $this->usuario->hasRole('ROLE_SUPER_ADMIN')) {
                        $menu[ strtoupper($equipo->getNombreCompleto()) ]->addChild(
                          'Ver Intervenciones',
                          [
                            'route' => 'intervencion_equipo_index',
                            'routeParameters' => ['id' => $equipo->getId()]
                          ]
                        )->setExtra('icon', 'fa  fa-wrench');
                    }
                    if ($this->securityManager->isGranted($this->rol, 'novedad_nueva') || $this->usuario->hasRole('ROLE_SUPER_ADMIN')) {
                        $menu[ strtoupper($equipo->getNombreCompleto()) ]->addChild(
                          'Registra Novedades',
                          array( 'route' => 'novedad_nueva', 'routeParameters' => array( 'id' => $equipo->getId() ) )
                      )->setExtra('icon', 'fa  fa-bell-o');
                    }
                    if ($equipo->estaInterviniendo()) {
                        $intervencionActual = $equipo->getUltimaIntervencion();

                        $device = $this->container->get('mobile_detect.mobile_detector');

                        $lat = $intervencionActual->getPozo()->getLatitud();

                        $lng = $intervencionActual->getPozo()->getLongitud();

                        $uri = "http://maps.google.com?q=$lat,$lng";

                        if ($device->isMobile()) {
                            if ($device->isAndroidOS()) {
                                $uri = "google.navigation:q=$lat,$lng";
                            } else {
                                $uri = "comgooglemaps://?q=$lat,$lng";
                            }
                        }


                        $menu[ strtoupper($equipo->getNombreCompleto()) ]->addChild(
                            'Ver en Google Maps',
                            array( 'uri' => $uri )
                        )->setExtra('icon', 'fa  fa-map-pin')
                         ->setLinkAttributes(array('target'=>'_blank'));
                    }
                }
            }

            //Se genera  el resto del menu desde la tabla usr_menu

            $itemsQuery = $em->getRepository('UsuarioBundle:Menu')->getRootsActivos()->getQuery();

            $items = $itemsQuery->getResult();

            foreach ($items as $item) {
                $this->generaMenu($item, $menu);
            }
        }

        return $menu;
    }

    public function generaMenu(Menu $nodoRaiz, $menuBuilder)
    {
        if (! $nodoRaiz->tieneHijosActivos()) {
            if ($nodoRaiz->esLink()) {
                if ($nodoRaiz->getPadre() && !$nodoRaiz->getPadre()->esHeader()) {
                    $key = $nodoRaiz->getPadre()->getNombre();

                    $menuBuilder = $menuBuilder[$key];
                }

                $ruta = $nodoRaiz->getAccion()->getRuta();

                if ($this->securityManager->isGranted($this->rol, $ruta) || $this->usuario->hasRole('ROLE_SUPER_ADMIN')) {
                    $menuBuilder->addChild($nodoRaiz->getNombre(), array('route' => $ruta))->setExtra('icon', 'fa ' . $nodoRaiz->getClaseIcono());
                }
            }
        } else {
            $esHeader = $nodoRaiz->esHeader();

            if ($esHeader) {
                $menuBuilder->addChild($nodoRaiz->getNombre())->setAttribute('class', 'header');
            } else {
                if (!$nodoRaiz->getPadre()->esHeader()) {
                    $key = $nodoRaiz->getPadre()->getNombre();

                    $menuBuilder = $menuBuilder[$key];
                }

                $menuBuilder->addChild(
                    $nodoRaiz->getNombre(),
                    array(
                        'childrenAttributes' => array(
                            'class' => 'treeview-menu',
                        ),
                    )
                )
                ->setUri('#')
                ->setExtra('icon', 'fa ' . $nodoRaiz->getClaseIcono())
                ->setAttribute('class', 'treeview');
            }


            foreach ($nodoRaiz->getHijosActivos() as $item) {
                $this->generaMenu($item, $menuBuilder);
            }


            if (!$esHeader) {
                if (!$menuBuilder->getChild($nodoRaiz->getNombre())->hasChildren()) {
                    $menuBuilder->removeChild($nodoRaiz->getNombre());
                }
            } else {
                $ultimoNodo = $menuBuilder->getLastChild()->getName();

                if ($ultimoNodo == $nodoRaiz->getNombre()) {
                    $menuBuilder->removeChild($ultimoNodo);
                }
            }
        }
    }
}
