<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Target;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Target controller.
 *
 * @Route("target")
 */
class TargetController extends Controller
{
    /**
     * Lists all target entities.
     *
     * @Route("/", name="target_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $target = $em->getRepository('ApiBundle:Target')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $target = $serializer->serialize($target, 'json');
        
                $response =  new Response($target, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Creates a new target entity.
     *
     * @Route("/new", name="target_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $target = $serializer->deserialize($data,'ApiBundle\Entity\Target', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $target->setProduct($this->getDoctrine()
        ->getRepository('ApiBundle:Product')
        ->findOneBy(['id' => $target->getProduct()->getId()]));
        $target->setRegion($this->getDoctrine()
        ->getRepository('ApiBundle:Region')
        ->findOneBy(['id' => $target->getRegion()->getId()]));
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($target);
    
        // Save our target
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a target entity.
     *
     * @Route("/{id}", name="target_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $target = $this->getDoctrine()
        ->getRepository('ApiBundle:Target')
        ->findOneBy(['id' => $id]);
    
        if ($target === null) {
            return new JsonResponse("target not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $target = $serializer->serialize($target, 'json');
    
      $response =  new Response($target, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing target entity.
     *
     * @Route("/{id}/edit", name="target_edit")
     * @Method({"PUT"})
     */
    public function editAction(Request $request, $id)
    {
        $target = $this->getDoctrine()
        ->getRepository('ApiBundle:Target')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to target object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Target', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $target->setQuantity($entity->getQuantity());
        $target->setStartDate($entity->getStartDate()); 
        $target->setEndDate($entity->getEndDate());
        $target->setDescription($entity->getDescription());
        $target->setStatus($entity->getStatus());
        $target->setProduct($this->getDoctrine()
        ->getRepository('ApiBundle:Product')
        ->findOneBy(['id' => $entity->getProduct()->getId()]));
        $target->setRegion($this->getDoctrine()
        ->getRepository('ApiBundle:Region')
        ->findOneBy(['id' => $entity->getRegion()->getId()]));


        // Save our target
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);

    }

    /**
     * Deletes a target entity.
     *
     * @Route("/{id}", name="target_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request,$id)
    {
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $target = $this->getDoctrine()->getRepository('ApiBundle:Target')->find($id);
      if (empty($target)) {
        $response =  new JsonResponse('target not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($target);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }

   
}
