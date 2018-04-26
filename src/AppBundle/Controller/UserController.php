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

        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {
            return new JsonResponse('user not found or not authenticate ...', Response::HTTP_NOT_FOUND);
        }

        $em = $this->getDoctrine()->getManager();
        
        $user = $em->getRepository('AppBundle:User')->findAll();
        
        $serializer = SerializerBuilder::create()->build();
        $user = $serializer->serialize($user, 'json');
        
        return new Response($user, Response::HTTP_OK);
    }


     /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"POST"})
     * @return Response
     */
    /* public function newAction(Request $request)
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

        } */





        /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"POST"})
     * @return Response
     */
    public function newAction(Request $request)
    {

        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {
            return new JsonResponse('user not found or not authenticate ...', Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();

        //now we want to deserialize data request to article object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'AppBundle\Entity\User', 'json');


            $user = new User();
            $user->setUsername($entity->getUsername());
            $user->setLogin($entity->getLogin());
            $user->setEmail($entity->getEmail());
            $user->setPassword($entity->getPassword());
            $user->setPlainPassword($entity->getPlainPassword());
            $user->setTypeUser($entity->getTypeUser());
            if ($entity->getTypeUser() === 3) {
                $user->addRole("ROLE_ADMIN");
            }
            $user->setPhone($entity->getPhone());
            $user->setEnabled(true);
            $this->get('fos_user.user_manager')->updateUser($user);

            //$em->persist($entity);
           // $em->flush();

            return new JsonResponse("created new user id".$user->getId(), Response::HTTP_OK);;

        }

        /**
         * Finds and displays a user entity.
         *
         * @Route("/session", name="user_session_show")
         * @Method({"GET"})
         */
        public function sessionUserAction()
        {

            $user = $this->get('security.context')->getToken()->getUser();
            if (!is_object($user)) {
                return new JsonResponse('go to login ...', Response::HTTP_NOT_FOUND);
            }

            return new Response($user, Response::HTTP_OK);
        }


        /**
         * Finds and displays a user entity.
         *
         * @Route("/{id}", name="user_show")
         * @Method({"GET"})
         */
        public function showAction($id)
        {

            $user = $this->get('security.context')->getToken()->getUser();
            if (!is_object($user)) {
                return new JsonResponse('go to login ...', Response::HTTP_NOT_FOUND);
            }
        
            $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(['id' => $id]);

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
     * @Method({"PUT"})
     */
    public function editAction(Request $request, $id)
    {

        if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return new JsonResponse('Administrator page !!!',Response::HTTP_OK);
          }
        $user = $this->get('security.context')->getToken()->getUser();
        if (!is_object($user)) {
            return new JsonResponse('user not found or not authenticate ...', Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();

        //now we want to deserialize data request to activity object ...
        $serializer = SerializerBuilder::create()->build();
        $newEntity = $serializer->deserialize($data,'AppBundle\Entity\User', 'json');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);
        $userManager = $this->get('fos_user.user_manager');

        if (!$entity) {
            return new JsonResponse('any user with this id ...', Response::HTTP_NOT_FOUND);
        }
            $user =  $this->userManager->findUserByUsername($entity->getUsername());

            $user->setUsername($newEntity->getUsername());
            $user->setEmail($newEntity->getEmail());
            $user->setPassword($newEntity->getPassword());
            $user->setPlainPassword($newEntity->getPlainPassword());
            $user->setPhone($newEntity->getPhone());
            $user->setTypeUser($newEntity->getTypeUser());
            if ($newEntity->getTypeUser() != $entity->getTypeUser()) {
                if ($newEntity->getTypeUser() === 3) {
                    $user->addRole("ROLE_ADMIN");
                }else {
                    $user->addRole("ROLE_USER");
                }
            }
            $this->userManager->updateUser($user);

            $em->flush();
        return null;
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $id)
    {
       /*  if (!$this->get('security.context')->isGranted('ROLE_ADMIN')) {
            return new JsonResponse('Administrator page !!!',Response::HTTP_OK);
          }
 */
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
