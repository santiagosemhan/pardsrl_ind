<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Maniobra;
use AppBundle\Form\ManiobraType;

/**
 * Maniobra controller.
 *
 */
class ManiobraController extends Controller
{
    /**
     * Lists all Maniobra entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $maniobras = $em->getRepository('AppBundle:Maniobra')->findAll();

        $paginator = $this->get('knp_paginator');

        $maniobras = $paginator->paginate(
            $maniobras,
            $request->query->get('page', 1)/* page number */,
            10/* limit per page */
        );

        $deleteForm = $this->createDeleteForm();

        return $this->render('AppBundle:maniobra:index.html.twig', array(
            'maniobras' => $maniobras,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Creates a new Maniobra entity.
     *
     */
    public function newAction(Request $request)
    {
        $maniobra = new Maniobra();
        $form = $this->createForm(ManiobraType::class, $maniobra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($maniobra);
            $em->flush();

            // set flash messages
            $this->get('session')->getFlashBag()->add('success', 'El registro se ha guardado satisfactoriamente.');

            return $this->redirectToRoute('maniobra_index');

        }

        return $this->render('AppBundle:maniobra:new.html.twig', array(
            'maniobra' => $maniobra,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Maniobra entity.
     *
     */
    public function showAction(Maniobra $maniobra)
    {


        return $this->render('AppBundle:maniobra:show.html.twig', array(
            'maniobra' => $maniobra
        ));
    }

    /**
     * Displays a form to edit an existing Maniobra entity.
     *
     */
    public function editAction(Request $request, Maniobra $maniobra)
    {
        $deleteForm = $this->createDeleteForm($maniobra);
        $editForm = $this->createForm(ManiobraType::class, $maniobra);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($maniobra);
            $em->flush();

            // set flash messages
            $this->get('session')->getFlashBag()->add('success', 'El registro se ha actualizado satisfactoriamente.');

            return $this->redirectToRoute('maniobra_edit', array('id' => $maniobra->getId()));
        }

        return $this->render('AppBundle:maniobra:edit.html.twig', array(
            'maniobra' => $maniobra,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Maniobra entity.
     *
     */
    public function deleteAction(Request $request, Maniobra $maniobra)
    {
        $form = $this->createDeleteForm($maniobra);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $em = $this->getDoctrine()->getManager();
                $em->remove($maniobra);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'El registro se ha dado de baja satisfactoriamente.');
            }catch(\Exception $e){
                $this->get('session')->getFlashBag()->add('error', 'Hubo un error al intentar eliminar el registro.');
            }
        }

        return $this->redirectToRoute('maniobra_index');
    }

    /**
     * Creates a form to delete a Maniobra entity.
     *
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('maniobra_delete', array('id' => '__obj_id__')))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
