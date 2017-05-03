<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use AppBundle\Entity\Instrumento;
use AppBundle\Form\InstrumentoType;

/**
 * Instrumento controller.
 *
 */
class InstrumentoController extends Controller
{
    /**
     * Lists all Instrumento entities.
     *
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $instrumentos = $em->getRepository('AppBundle:Instrumento')->findAll();

        $paginator = $this->get('knp_paginator');

        $instrumentos = $paginator->paginate(
            $instrumentos,
            $request->query->get('page', 1)/* page number */,
            10/* limit per page */
        );

        $deleteForm = $this->createDeleteForm();

        return $this->render('AppBundle:instrumento:index.html.twig', array(
            'instrumentos' => $instrumentos,
            'delete_form' => $deleteForm->createView()
        ));
    }

    /**
     * Creates a new Instrumento entity.
     *
     */
    public function newAction(Request $request)
    {
        $instrumento = new Instrumento();
        $form = $this->createForm(InstrumentoType::class, $instrumento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($instrumento);
            $em->flush();

            // set flash messages
            $this->get('session')->getFlashBag()->add('success', 'El registro se ha guardado satisfactoriamente.');

            return $this->redirectToRoute('instrumento_index');

        }

        return $this->render('AppBundle:instrumento:new.html.twig', array(
            'instrumento' => $instrumento,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Instrumento entity.
     *
     */
    public function showAction(Instrumento $instrumento)
    {


        return $this->render('AppBundle:instrumento:show.html.twig', array(
            'instrumento' => $instrumento
        ));
    }

    /**
     * Displays a form to edit an existing Instrumento entity.
     *
     */
    public function editAction(Request $request, Instrumento $instrumento)
    {
        $deleteForm = $this->createDeleteForm($instrumento);
        $editForm = $this->createForm(InstrumentoType::class, $instrumento);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($instrumento);
            $em->flush();

            // set flash messages
            $this->get('session')->getFlashBag()->add('success', 'El registro se ha actualizado satisfactoriamente.');

            return $this->redirectToRoute('instrumento_edit', array('id' => $instrumento->getId()));
        }

        return $this->render('AppBundle:instrumento:edit.html.twig', array(
            'instrumento' => $instrumento,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Instrumento entity.
     *
     */
    public function deleteAction(Request $request, Instrumento $instrumento)
    {
        $form = $this->createDeleteForm($instrumento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $em = $this->getDoctrine()->getManager();
                $em->remove($instrumento);
                $em->flush();

                $this->get('session')->getFlashBag()->add('success', 'El registro se ha dado de baja satisfactoriamente.');
            }catch(\Exception $e){
                $this->get('session')->getFlashBag()->add('error', 'Hubo un error al intentar eliminar el registro.');
            }
        }

        return $this->redirectToRoute('instrumento_index');
    }

    /**
     * Creates a form to delete a Instrumento entity.
     *
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm()
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('instrumento_delete', array('id' => '__obj_id__')))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
