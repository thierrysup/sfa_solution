<?php

namespace AppBundle\Controller;

use AppBundle\Entity\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;


/**
 * Usermanager controller.
 *
 * @Route("usermanager")
 */
class UserManagerController extends Controller
{
    /**
     * Lists all userManager entities.
     *
     * @Route("/", name="usermanager_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $userManagers = $em->getRepository('AppBundle:UserManager')->findAll();
        
        $serializer = SerializerBuilder::create()->build();
        $userManagers = $serializer->serialize($userManagers, 'json');
        
        $response =  new Response($userManagers, Response::HTTP_OK);
        return $response;
    }

    /**
     * Creates a new userManager entity.
     *
     * @Route("/new", name="usermanager_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
            
        $serializer = SerializerBuilder::create()->build();
        $userManager = $serializer->deserialize($data,'AppBundle\Entity\UserManager', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $userManager->setManager($this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->findOneBy(['id' => $userManager->getManager()->getId()]));
        $userManager->setSubordinate($this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->findOneBy(['id' => $userManager->getSubordinate()->getId()]));

        $userManager->setActivity($this->getDoctrine()
        ->getRepository('ApiBundle:User')
        ->findOneBy(['id' => $userManager->getActivity()->getId()]));
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($userManager);
    
        // Save our article
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     

     return $response;
    }

    /**
     * Finds and displays a userManager entity.
     *
     * @Route("/{id}", name="usermanager_show")
     * @Method("GET")
     */
    public function showAction($id)
    {


        $userManager = $this->getDoctrine()
        ->getRepository('AppBundle:UserManager')
        ->findOneBy(['id' => $id]);
    
        if ($userManager === null) {
            return new JsonResponse("userManager not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $userManager = $serializer->serialize($userManager, 'json');
    
      $response =  new Response($userManager, Response::HTTP_OK);
      

      return $response;
    }

    /**
     * Displays a form to edit an existing userManager entity.
     *
     * @Route("/{id}/edit", name="usermanager_edit")
     * @Method({"PUT"})
     */
    public function editAction(Request $request,$id)
    {
        $userManager = $this->getDoctrine()
        ->getRepository('AppBundle:UserManager')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to article object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'AppBundle\Entity\UserManger', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $userManager->setManager($this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->findOneBy(['id' => $entity->getUserManager()->getId()]));

        $userManager->setSubordinate($this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->findOneBy(['id' => $entity->getSubordinate()->getId()]));
        
        $userManager->setActivity($this->getDoctrine()
        ->getRepository('ApiBundle:Activity')
        ->findOneBy(['id' => $entity->getActivity()->getId()]));
        // Save our article
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
     

      return $response;
    }

    /**
     * Deletes a userManager entity.
     *
     * @Route("/{id}", name="usermanager_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
      $em = $this->getDoctrine()->getManager();
      $userManager = $this->getDoctrine()->getRepository('AppBundle:UserManager')->find($id);
      if (empty($userManager)) {
        $response =  new JsonResponse('userManager not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($userManager);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }
}
