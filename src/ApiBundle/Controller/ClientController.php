<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;


/**
 * Client controller.
 *
 * @Route("client")
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/", name="client_index")
     * @Method("GET")
     * 
     * @return Response
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository('ApiBundle:Client')->findAll();
        //$data = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $clients = $serializer->serialize($clients, 'json');

        $response =  new Response($clients);


        return $response;
    }

    /**
     * Creates a new client entity.
     *
     * @Route("/new", name="client_new")
     * @Method({"POST","OPTIONS"})
     * @return Response
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
            
            $serializer = SerializerBuilder::create()->build();
            $client = $serializer->deserialize($data,'ApiBundle\Entity\Client', 'json');

            // Get the Doctrine service and manager
            $em = $this->getDoctrine()->getManager();
        
            // Add our quote to Doctrine so that it can be saved
            $em->persist($client);
        
            // Save our client
            $em->flush();

            $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);

            return $response;

    }

    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}", name="client_show")
     * @param $id
     * @Method({"GET"})
     * @return Response
     */
    public function showAction($id)
    {
        $client = $this->getDoctrine()
        ->getRepository('ApiBundle:Client')
        ->findOneBy(['id' => $id]);


    
        if ($client === null) {
            return new JsonReponse("client not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $client = $serializer->serialize($client, 'json');
    
      $response =  new Response($client, Response::HTTP_OK);
      
      return $response;
    }

    /**
     * Displays a form to edit an existing client entity.
     *
     * @Route("/{id}/edit", name="client_edit")
     * @Method({"PUT"})
     * @return Response
     */
    public function editAction(Request $request,$id)
    {
        $client = $this->getDoctrine()
        ->getRepository('ApiBundle:Client')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to client object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Client', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $client->setName($entity->getName());
        $client->setAge($entity->getAge()); 
        
        // Save our client
         $em->flush();
        $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
  
        return $response;
    }

    /**
     * Deletes a client entity.
     *
     * @Route("/{id}", name="client_delete")
     * @Method({"DELETE"})
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $client = $this->getDoctrine()->getRepository('ApiBundle:Client')->find($id);
      if (empty($client)) {
        $response =  new JsonResponse("client not found", Response::HTTP_NOT_FOUND);
  
        return $response;
       }
       else {
        $em->remove($client);
        $em->flush();
       }
        $response =  new JsonResponse("deleted successfully", Response::HTTP_OK);
        
        return $response;
    }
}
