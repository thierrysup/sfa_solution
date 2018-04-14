<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\ActivityUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Activityuser controller.
 *
 * @Route("activityuser")
 */
class ActivityUserController extends Controller
{
    /**
     * Lists all activityUser entities.
     *
     * @Route("/", name="activityuser_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $activityUsers = $em->getRepository('ApiBundle:ActivityUser')->findAll();

        return $this->render('activityuser/index.html.twig', array(
            'activityUsers' => $activityUsers,
        ));
    }

    /**
     * Creates a new activityUser entity.
     *
     * @Route("/new", name="activityuser_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $activityUser = new Activityuser();
        $form = $this->createForm('ApiBundle\Form\ActivityUserType', $activityUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($activityUser);
            $em->flush($activityUser);

            return $this->redirectToRoute('activityuser_show', array('id' => $activityUser->getId()));
        }

        return $this->render('activityuser/new.html.twig', array(
            'activityUser' => $activityUser,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a activityUser entity.
     *
     * @Route("/{id}", name="activityuser_show")
     * @Method("GET")
     */
    public function showAction(ActivityUser $activityUser)
    {
        $deleteForm = $this->createDeleteForm($activityUser);

        return $this->render('activityuser/show.html.twig', array(
            'activityUser' => $activityUser,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing activityUser entity.
     *
     * @Route("/{id}/edit", name="activityuser_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ActivityUser $activityUser)
    {
        $deleteForm = $this->createDeleteForm($activityUser);
        $editForm = $this->createForm('ApiBundle\Form\ActivityUserType', $activityUser);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activityuser_edit', array('id' => $activityUser->getId()));
        }

        return $this->render('activityuser/edit.html.twig', array(
            'activityUser' => $activityUser,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a activityUser entity.
     *
     * @Route("/{id}", name="activityuser_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ActivityUser $activityUser)
    {
        $form = $this->createDeleteForm($activityUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($activityUser);
            $em->flush($activityUser);
        }

        return $this->redirectToRoute('activityuser_index');
    }

    /**
     * Creates a form to delete a activityUser entity.
     *
     * @param ActivityUser $activityUser The activityUser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ActivityUser $activityUser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('activityuser_delete', array('id' => $activityUser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
