<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Quarter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Quarter controller.
 *
 * @Route("quarter")
 */
class QuarterController extends Controller
{
    /**
     * Lists all quarter entities.
     *
     * @Route("/", name="quarter_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $quarters = $em->getRepository('ApiBundle:Quarter')->findAll();

        return $this->render('quarter/index.html.twig', array(
            'quarters' => $quarters,
        ));
    }

    /**
     * Creates a new quarter entity.
     *
     * @Route("/new", name="quarter_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $quarter = new Quarter();
        $form = $this->createForm('ApiBundle\Form\QuarterType', $quarter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($quarter);
            $em->flush($quarter);

            return $this->redirectToRoute('quarter_show', array('id' => $quarter->getId()));
        }

        return $this->render('quarter/new.html.twig', array(
            'quarter' => $quarter,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a quarter entity.
     *
     * @Route("/{id}", name="quarter_show")
     * @Method("GET")
     */
    public function showAction(Quarter $quarter)
    {
        $deleteForm = $this->createDeleteForm($quarter);

        return $this->render('quarter/show.html.twig', array(
            'quarter' => $quarter,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing quarter entity.
     *
     * @Route("/{id}/edit", name="quarter_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Quarter $quarter)
    {
        $deleteForm = $this->createDeleteForm($quarter);
        $editForm = $this->createForm('ApiBundle\Form\QuarterType', $quarter);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quarter_edit', array('id' => $quarter->getId()));
        }

        return $this->render('quarter/edit.html.twig', array(
            'quarter' => $quarter,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a quarter entity.
     *
     * @Route("/{id}", name="quarter_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Quarter $quarter)
    {
        $form = $this->createDeleteForm($quarter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($quarter);
            $em->flush($quarter);
        }

        return $this->redirectToRoute('quarter_index');
    }

    /**
     * Creates a form to delete a quarter entity.
     *
     * @param Quarter $quarter The quarter entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Quarter $quarter)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('quarter_delete', array('id' => $quarter->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
