<?php

namespace UsuarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use UsuarioBundle\Entity\Usuario;
use UsuarioBundle\Event\UsuarioPasswordModificadoEvent;
use UsuarioBundle\Form\CambiarPasswordType;

class CambiarPasswordController extends Controller
{
    public function cambiarPasswordAction(Request $request, Usuario $usuario)
    {
        $form = $this->createForm(CambiarPasswordType::class, $usuario);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->changePassword($usuario);

            return $this->redirectToRoute('persona_index');
        }

        return $this->render('UsuarioBundle:CambiarPassword:cambiarPassword.html.twig', array(
            'usuario' => $usuario,
            'form' => $form->createView(),
        ));
    }

    public function cambiarPasswordFormAction(Request $request)
    {
        $updated = false;

        $usuario = $this->getUser();

        $form = $this->createForm(CambiarPasswordType::class, $usuario, [
            'action' => $this->generateUrl('cambiar_password_form'),
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->changePassword($usuario);

            $updated = true;
        }


        $formContent = $this->renderView('UsuarioBundle:CambiarPassword:cambiar-password-form.html.twig', array(
            'form' => $form->createView(),
        ));

        return $this->json([
            'content' => $formContent,
            'updated' => $updated,
            'valid' => $form->isValid(),
            'submitted' => $form->isSubmitted(),
            'errors' => $form->getErrors()
        ]);
    }



    private function changePassword(Usuario $usuario)
    {
        $userManager = $this->get('fos_user.user_manager');

        $dispatcher = $this->get('event_dispatcher');

        $event = new UsuarioPasswordModificadoEvent($usuario);

        $dispatcher->dispatch(UsuarioPasswordModificadoEvent::NAME, $event);

        $userManager->updateUser($usuario);

        $this->get('session')->getFlashBag()->add('success', 'La contraseña se ha modificado satisfactoriamente. Puede ingresar nuevamente con su nueva contraseña.');
    }
}
