<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\ProductSurvey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Productsurvey controller.
 *
 * @Route("productsurvey")
 */
class ProductSurveyController extends Controller
{
    /**
     * Lists all productSurvey entities.
     *
     * @Route("/", name="productsurvey_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $productSurveys = $em->getRepository('ApiBundle:ProductSurvey')->findAll();

        return $this->render('productsurvey/index.html.twig', array(
            'productSurveys' => $productSurveys,
        ));
    }

    /**
     * Creates a new productSurvey entity.
     *
     * @Route("/new", name="productsurvey_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $productSurvey = new Productsurvey();
        $form = $this->createForm('ApiBundle\Form\ProductSurveyType', $productSurvey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($productSurvey);
            $em->flush($productSurvey);

            return $this->redirectToRoute('productsurvey_show', array('id' => $productSurvey->getId()));
        }

        return $this->render('productsurvey/new.html.twig', array(
            'productSurvey' => $productSurvey,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a productSurvey entity.
     *
     * @Route("/{id}", name="productsurvey_show")
     * @Method("GET")
     */
    public function showAction(ProductSurvey $productSurvey)
    {
        $deleteForm = $this->createDeleteForm($productSurvey);

        return $this->render('productsurvey/show.html.twig', array(
            'productSurvey' => $productSurvey,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing productSurvey entity.
     *
     * @Route("/{id}/edit", name="productsurvey_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProductSurvey $productSurvey)
    {
        $deleteForm = $this->createDeleteForm($productSurvey);
        $editForm = $this->createForm('ApiBundle\Form\ProductSurveyType', $productSurvey);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('productsurvey_edit', array('id' => $productSurvey->getId()));
        }

        return $this->render('productsurvey/edit.html.twig', array(
            'productSurvey' => $productSurvey,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a productSurvey entity.
     *
     * @Route("/{id}", name="productsurvey_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProductSurvey $productSurvey)
    {
        $form = $this->createDeleteForm($productSurvey);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($productSurvey);
            $em->flush($productSurvey);
        }

        return $this->redirectToRoute('productsurvey_index');
    }

    /**
     * Creates a form to delete a productSurvey entity.
     *
     * @param ProductSurvey $productSurvey The productSurvey entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProductSurvey $productSurvey)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('productsurvey_delete', array('id' => $productSurvey->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
