<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Town;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Town controller.
 *
 * @Route("town")
 */
class TownController extends Controller
{
    /**
     * Lists all town entities.
     *
     * @Route("/", name="town_index")
     * @Method("GET")
     */
    public function indexAction()
    {
         $em = $this->getDoctrine()->getManager();
        
                $towns = $em->getRepository('ApiBundle:Town')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $towns = $serializer->serialize($towns, 'json');
        
                $response =  new Response($towns, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Creates a new town entity.
     *
     * @Route("/new", name="town_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $town = $serializer->deserialize($data,'ApiBundle\Entity\Town', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $town->setRegion($this->getDoctrine()
        ->getRepository('ApiBundle:Region')
        ->findOneBy(['id' => $town->getRegion()->getId()]));
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($town);
    
        // Save our country
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;

    }

    /**
     * Finds and displays a town entity.
     *
     * @Route("/{id}", name="town_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $town = $this->getDoctrine()
        ->getRepository('ApiBundle:Town')
        ->findOneBy(['id' => $id]);
    
        if ($town === null) {
            return new JsonResponse("town not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $town = $serializer->serialize($town, 'json');
    
      $response =  new Response($town, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing town entity.
     *
     * @Route("/{id}/edit", name="town_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        
        $town = $this->getDoctrine()
        ->getRepository('ApiBundle:Town')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to town object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Town', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $town->setName($entity->getName());
        $town->setDescription($entity->getDescription()); 
        $town->setStatus($entity->getStatus());
        $town->setTown($this->getDoctrine()
        ->getRepository('ApiBundle:Town')
        ->findOneBy(['id' => $entity->getTown()->getId()]));
        
        // Save our town
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
         
    }

    /**
     * Deletes a town entity.
     *
     * @Route("/{id}", name="town_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Town $town)
    {
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $town = $this->getDoctrine()->getRepository('ApiBundle:Town')->find($id);
      if (empty($town)) {
        $response =  new JsonResponse('town not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($town);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }

}
