<?php

namespace UsuarioBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Doctrine\ORM\Query;
use UsuarioBundle\Entity\Accion;

class MilentarSeguridadUpdateAccionesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('milentar:seguridad:update-acciones')
            ->setDescription('Actualiza acciones en base a las rutas del sistema.')
            //->addArgument('argument', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('status', null, InputOption::VALUE_NONE, 'Retorna el estado actual de las acciones vs las rutas de sf.')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Actualiza las acciones a partir de las rutas de sf.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $force  = $input->hasOption('force') ? $input-> getOption('force') : null;

        $status = $input->hasOption('status') ? $input->getOption('status') : null;

        if ($status || $force) {
            $router = $this->getContainer()->get('router');

            $em = $this->getContainer()->get('doctrine')->getManager();

            $acciones = $em->getRepository('UsuarioBundle:Accion')->getQb()->getQuery()->getResult();

            $routesCollection = $router->getRouteCollection();

            $aRows = [];

            $updates = 0;

            foreach ($routesCollection->all() as $routeName => $route) {
                if ($route->hasDefault('_controller') && !preg_match("/^(_|fos|root|usuario_acceso_denegado)/", $routeName)) {
                    $upd = false;

                    foreach ($acciones as $accion) {
                        if ($accion->getRuta() == $routeName) {
                            $upd = true;
                            break;
                        }
                    }



                    if (!$upd && $force) {
                        $accion = new Accion();

                        $routeStr = strtoupper(preg_replace("/(-|_)/", "_", $routeName));

                        $accion->setNombre($routeStr);

                        $accion->setRuta($routeName);

                        $accion->setActivo(true);

                        $em->persist($accion);

                        $em->flush();

                        $updates++;

                        $upd =true;
                    }

                    $sync = $upd ? '<info>OK</info>' : '<error>NO</error>';

                    $accion = $upd ? $accion->getNombre() : null;

                    $aRows[] = [$routeName,$route->getPath(),$accion,$sync];
                }
            }


            $table = new Table($output);

            $table->setHeaders(['Ruta','Url','Accion','Sync']);

            $table->setRows($aRows);

            $table->render();

            if ($force) {
                $output->writeln("<comment>Se han actualizado $updates acciones.</comment>");
            }

            //$output->writeln('<info>Actualizacion completa.</info>');
        } else {
            $output->writeln('<comment>No ha especificado ni una opción. Si desea ver el estado actual de las acciones utilice el argumento <info>--status</info></comment>');
            $output->writeln('<comment>Para actualizar utilice <info>--force</info>.</comment>');
        }
    }
}
