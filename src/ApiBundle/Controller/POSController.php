<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\POS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Po controller.
 *
 * @Route("pos")
 */
class POSController extends Controller
{
    /**
     * Lists all pO entities.
     *
     * @Route("/", name="pos_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $pOSs = $em->getRepository('ApiBundle:POS')->findAll();

        return $this->render('pos/index.html.twig', array(
            'pOSs' => $pOSs,
        ));
    }

    /**
     * Creates a new pO entity.
     *
     * @Route("/new", name="pos_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $pO = new Po();
        $form = $this->createForm('ApiBundle\Form\POSType', $pO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($pO);
            $em->flush($pO);

            return $this->redirectToRoute('pos_show', array('id' => $pO->getId()));
        }

        return $this->render('pos/new.html.twig', array(
            'pO' => $pO,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a pO entity.
     *
     * @Route("/{id}", name="pos_show")
     * @Method("GET")
     */
    public function showAction(POS $pO)
    {
        $deleteForm = $this->createDeleteForm($pO);

        return $this->render('pos/show.html.twig', array(
            'pO' => $pO,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing pO entity.
     *
     * @Route("/{id}/edit", name="pos_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, POS $pO)
    {
        $deleteForm = $this->createDeleteForm($pO);
        $editForm = $this->createForm('ApiBundle\Form\POSType', $pO);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('pos_edit', array('id' => $pO->getId()));
        }

        return $this->render('pos/edit.html.twig', array(
            'pO' => $pO,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a pO entity.
     *
     * @Route("/{id}", name="pos_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, POS $pO)
    {
        $form = $this->createDeleteForm($pO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($pO);
            $em->flush($pO);
        }

        return $this->redirectToRoute('pos_index');
    }

    /**
     * Creates a form to delete a pO entity.
     *
     * @param POS $pO The pO entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(POS $pO)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pos_delete', array('id' => $pO->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
