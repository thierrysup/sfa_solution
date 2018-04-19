<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Country;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;
/**
 * Country controller.
 *
 * @Route("/country")
 */
class CountryController extends Controller
{
    /**
     * Lists all country entities.
     *
     * @Route("/", name="country_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $countries = $em->getRepository('ApiBundle:Country')->findAll();

        $serializer = SerializerBuilder::create()->build();
        $countries = $serializer->serialize($countries, 'json');

        $response =  new Response($countries, Response::HTTP_OK);        
        return $response;
    }

    /**
     * Creates a new country entity.
     *
     * @Route("/new", name="country_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $country = $serializer->deserialize($data,'ApiBundle\Entity\Country', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($country);
    
        // Save our country
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a country entity.
     *
     * @Route("/{id}", name="country_show")
     * @Method("GET")
     */
    public function showAction($id)
    {

        $country = $this->getDoctrine()
        ->getRepository('ApiBundle:Country')
        ->findOneBy(['id' => $id]);
    
        if ($country === null) {
            return new JsonResponse("country not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $country = $serializer->serialize($country, 'json');
    
      $response =  new Response($country, Response::HTTP_OK);     

      return $response;
    }

    /**
     * Displays a form to edit an existing country entity.
     *
     * @Route("/{id}/edit", name="country_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {   
        $country = $this->getDoctrine()
        ->getRepository('ApiBundle:Country')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to country object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Country', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $country->setName($entity->getName());
        $country->setDescription($entity->getDescription()); 
        $country->setStatus($entity->getStatus());
        
        // Save our country
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
      return $response;
    }

    /**
     * Deletes a country entity.
     *
     * @Route("/{id}", name="country_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {   

        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $country = $this->getDoctrine()->getRepository('ApiBundle:Country')->find($id);
      if (empty($country)) {
        $response =  new JsonResponse('country not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($country);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }

}
