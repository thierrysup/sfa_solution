<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Role controller.
 *
 * @Route("role")
 */
class RoleController extends Controller
{
    /**
     * Lists all role entities.
     *
     * @Route("/", name="role_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $role = $em->getRepository('ApiBundle:Role')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $role = $serializer->serialize($role, 'json');
        
                $response =  new Response($role, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Creates a new role entity.
     *
     * @Route("/new", name="role_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $role = $serializer->deserialize($data,'ApiBundle\Entity\Role', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        // Add our quote to Doctrine so that it can be saved
        $em->persist($role);
    
        // Save our role
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a role entity.
     *
     * @Route("/{id}", name="role_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        
        $role = $this->getDoctrine()
        ->getRepository('ApiBundle:Role')
        ->findOneBy(['id' => $id]);
    
        if ($role === null) {
            return new JsonResponse("role not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $role = $serializer->serialize($role, 'json');
    
      $response =  new Response($role, Response::HTTP_OK);
      return $response;   

    }

    /**
     * Displays a form to edit an existing role entity.
     *
     * @Route("/{id}/edit", name="role_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        
        $role = $this->getDoctrine()
        ->getRepository('ApiBundle:Role')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to role object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Role', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $role->setName($entity->getName());
        $role->setStatus($entity->getStatus()); 
        $role->setPriority($entity->getPriority());
        
        // Save our role
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);


    }

    /**
     * Deletes a role entity.
     *
     * @Route("/{id}", name="role_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $role = $this->getDoctrine()->getRepository('ApiBundle:Role')->find($id);
      if (empty($role)) {
        $response =  new JsonResponse('role not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($role);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    
    }

}
