<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Region;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Region controller.
 *
 * @Route("region")
 */
class RegionController extends Controller
{
    /**
     * Lists all region entities.
     *
     * @Route("/", name="region_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $regions = $em->getRepository('ApiBundle:Region')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $regions = $serializer->serialize($regions, 'json');
        
                $response =  new Response($regions, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Creates a new region entity.
     *
     * @Route("/new", name="region_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $region = $serializer->deserialize($data,'ApiBundle\Entity\Region', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $region->setCountry($this->getDoctrine()
        ->getRepository('ApiBundle:Country')
        ->findOneBy(['id' => $region->getCountry()->getId()]));
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($region);
    
        // Save our country
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a region entity.
     *
     * @Route("/{id}", name="region_show")
     * @Method("GET")
     */
    public function showAction($id)
    {

        $region = $this->getDoctrine()
        ->getRepository('ApiBundle:Region')
        ->findOneBy(['id' => $id]);
    
        if ($region === null) {
            return new JsonResponse("region not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $region = $serializer->serialize($region, 'json');
    
      $response =  new Response($region, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing region entity.
     *
     * @Route("/{id}/edit", name="region_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $region = $this->getDoctrine()
        ->getRepository('ApiBundle:Region')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to article object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Region', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $region->setName($entity->getName());
        $region->setDescription($entity->getDescription()); 
        $region->setStatus($entity->getStatus());
        $region->setContry($this->getDoctrine()
        ->getRepository('ApiBundle:Country')
        ->findOneBy(['id' => $entity->getCountry()->getId()]));
        
        // Save our article
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
    }

    /**
     * Deletes a region entity.
     *
     * @Route("/{id}", name="region_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {

        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $region = $this->getDoctrine()->getRepository('ApiBundle:Region')->find($id);
      if (empty($region)) {
        $response =  new JsonResponse('region not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($region);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }

}
