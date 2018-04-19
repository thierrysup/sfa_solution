<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * User controller.
 *
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('AppBundle:User')->findAll();
        
        $serializer = SerializerBuilder::create()->build();
        $user = $serializer->serialize($user, 'json');
        
        $response =  new Response($user, Response::HTTP_OK);
        return $response;
    }


     /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"POST"})
     * @return Response
     */
    public function newAction(Request $request)
    {

            $userManager = $this->get('fos_user.user_manager');


            
            $email = $request->request->get('email');
            $username = $request->request->get('username');
            $password = $request->request->get('password');


            $email_exist = $userManager->findUserByEmail($email);
            $username_exist = $userManager->findUserByUsername($username);

            if($email_exist || $username_exist){
                $response = new JsonResponse();
                $response->setData("Username/Email ".$username."/".$email." existiert bereits");
                return $response;
            }

            $user = $userManager->createUser();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setEnabled(true); 
            $user->setPlainPassword($password);
            $userManager->updateUser($user, true);

            $response = new JsonResponse();
            $response->setData("User: ".$user->getUsername()." had been saved");
            return $response;

        }

        /**
         * Finds and displays a user entity.
         *
         * @Route("/{id}", name="user_show")
         * @Method({"GET"})
         */
        public function showAction($id)
        {
            $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['id' => $id]);


        
            if ($user === null) {
                return new JsonResponse("user not found", Response::HTTP_NOT_FOUND);
            }
            $serializer = SerializerBuilder::create()->build();
            $user = $serializer->serialize($user, 'json');
        
        $response =  new Response($user, Response::HTTP_OK);
        

        return $response;
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('AppBundle\Form\UserType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('user/edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $id)
    {
      $em = $this->getDoctrine()->getManager();
      $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
      if (empty($user)) {
        $response =  new JsonResponse('user not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($user);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }
}
