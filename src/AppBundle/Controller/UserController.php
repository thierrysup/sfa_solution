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

         $user = $this->get('security.token_storage')->getToken()->getUser();
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
     * Lists all activity entities.
     *
     * @Route("/page/{page}/{limit}", name="user_page_index")
     * @Method("GET")
     *
     * @return Response
     */
    public function indexPageAction($page,$limit)
    {
        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (!is_object($user)) {
            return new JsonResponse('user not found or not authenticate ...', Response::HTTP_NOT_FOUND);
        } 

        $em = $this->getDoctrine()->getManager();
        
                $users = $em->getRepository('AppBundle:User')->findAll();

                 //$serializer = SerializerBuilder::create()->build();
                 //$data = $serializer->serialize($activity, 'json');

                $response =  new Response($this->paginate($users,$page,$limit), Response::HTTP_OK);
            return $response;
    }

    public function paginate($array,$page,$limit){
        $pager=$this->get('knp_paginator');
        $paginated=$pager->paginate($array,$page, $limit);

        $resItems = ($paginated->getTotalItemCount()%$limit);
        $numPages = (int)($paginated->getTotalItemCount()/$limit);
        $totalPages = ($resItems === 0) ? $numPages : $numPages + 1 ;

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize([
            'page'=> intval($paginated->getCurrentPageNumber()),
            'relativesTotal'=> count($paginated->getItems()),
            'globalTotal'=> $paginated->getTotalItemCount(),
            'numberOfPages'  => $totalPages ,
            'limit'  => intval($limit),
            'items' => $paginated->getItems()
        ], 'json');
        return $data;
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
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return new JsonResponse('Administrator page !!!',Response::HTTP_OK);
          }

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (!is_object($user)) {
            return new JsonResponse('user not found or not authenticate ...', Response::HTTP_NOT_FOUND);
        } 

        $data = $request->getContent();

        //now we want to deserialize data request to article object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'AppBundle\Entity\User', 'json');

        //  var_dump($entity);
         // die();
        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->createUser();
        $user->setUsername($entity->getUsername());
        $user->setFirstname($entity->getFirstname());
        $user->setLastname($entity->getLastname());
        $user->setAddress($entity->getAddress());
        $user->setEmail($entity->getEmail());
        $user->setEmailCanonical($entity->getEmail());
        $user->setTypeUser($entity->getTypeUser());
        if ($entity->getTypeUser() === 3) {
            $user->addRole("ROLE_ADMIN");
        } else {
            $user->addRole("ROLE_USER");
        }
        $user->setPhone($entity->getPhone());
        $user->setEnabled(1); // enable the user or enable it later with a confirmation token in the email
        // this method will encrypt the password with the default settings :)
        $user->setPlainPassword($entity->getPassword());
        $userManager->updateUser($user);

            return new JsonResponse("created new user id".$user->getId(), Response::HTTP_OK);;

        }

        /**
         * Finds and displays a user entity.
         *
         * @Route("/session/{username}", name="user_session_show")
         * @Method({"GET"})
         */
        public function sessionUserAction($username)
        {

            $user = $this->get('security.token_storage')->getToken()->getUser();
            if ((!is_object($user)&&($user->getUsername() !== $username))) {
                return new JsonResponse('go to login ...', Response::HTTP_NOT_FOUND);
            }

            $serializer = SerializerBuilder::create()->build();
            $user = $serializer->serialize($user, 'json');
            // var_dump($user);
            // die();
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

             $user = $this->get('security.token_storage')->getToken()->getUser();
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

        $user = $this->get('security.token_storage')->getToken()->getUser();
        if (!is_object($user)) {
            return new JsonResponse('user not found or not authenticate ...', Response::HTTP_NOT_FOUND);
        }

        $data = $request->getContent();

        //now we want to deserialize data request to user object ...
        $serializer = SerializerBuilder::create()->build();
        $newEntity = $serializer->deserialize($data,'AppBundle\Entity\User', 'json');

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:User')->find($id);
       
        if (!$entity) {
            return new JsonResponse('any user with this id ...', Response::HTTP_NOT_FOUND);
        }

        $userManager = $this->get('fos_user.user_manager');
        $user = $userManager->findUserBy(array('id'=> $id));
           

            $user->setUsername($newEntity->getUsername());
            $user->setFirstname($newEntity->getFirstname());
            $user->setLastname($newEntity->getLastname());
            $user->setAddress($newEntity->getAddress());
            $user->setEmail($newEntity->getEmail());
            //$user->setPassword($newEntity->getPassword());
            if ($newEntity->getIsChange() === true) {
                $user->setPlainPassword($newEntity->getPassword());
            }
            $user->setPhone($newEntity->getPhone());
         
            if ($newEntity->getTypeUser() != $user->getTypeUser()) {
                $user->setTypeUser($newEntity->getTypeUser());
                if ($newEntity->getTypeUser() === 3) {
                    $user->addRole("ROLE_ADMIN");
                }else {
                    $user->addRole("ROLE_USER");
                }
            }
            // var_dump($newEntity);
            // die();
            $userManager->updateUser($user);

            $serializer = SerializerBuilder::create()->build();
            $user = $serializer->serialize($user, 'json');
        
           
        return new Response($user, Response::HTTP_OK);
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $id)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            return new JsonResponse('Administrator page !!!',Response::HTTP_OK);
          }
 
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
