<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Activity;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Activity controller.
 *
 * @Route("/activity")
 */
class ActivityController extends Controller
{
    /**
     * Lists all activity entities.
     *
     * @Route("/", name="activity_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $activity = $em->getRepository('ApiBundle:Activity')->findAll();

                $serializer = SerializerBuilder::create()->build();
                $data = $serializer->serialize($activity, 'json');

                $response =  new Response($data, Response::HTTP_OK);
        return $response;
    }

    /**
     * Creates a new activity entity.
     *
     * @Route("/new", name="activity_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $activity = $serializer->deserialize($data,'ApiBundle\Entity\Activity', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $activity->setEntreprise($this->getDoctrine()
        ->getRepository('ApiBundle:Entreprise')
        ->findOneBy(['id' => $activity->getEntreprise()->getId()]));
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($activity);
    
        // Save our activity
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;

    }

    /**
     * Finds and displays a activity entity.
     *
     * @Route("/{id}", name="activity_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $activity = $this->getDoctrine()
        ->getRepository('ApiBundle:Activity')
        ->findOneBy(['id' => $id]);
    
        if ($activity === null) {
            return new JsonResponse("activity not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $activity = $serializer->serialize($activity, 'json');
    
      $response =  new Response($activity, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing activity entity.
     *
     * @Route("/{id}/edit", name="activity_edit")
     * @Method({"PUT"})
     */
    public function editAction(Request $request, $id)
    {
        $activity = $this->getDoctrine()
        ->getRepository('ApiBundle:Activity')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to activity object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Activity', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $activity->setName($entity->getName());
        $activity->setStartDate($entity->getStartDate()); 
        $activity->setEndDate($entity->getEndDate());
        $activity->setType_activity($entity->getType_activity());
        $activity->setStatus($entity->getStatus());
        $activity->setEntreprise($this->getDoctrine()
        ->getRepository('ApiBundle:Entreprise')
        ->findOneBy(['id' => $entity->getEntreprise()->getId()]));


        // Save our activity
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);

    }

    /**
     * Deletes a activity entity.
     *
     * @Route("/{id}", name="activity_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        // $logic_service = $this->get('logic_services');
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $activity = $this->getDoctrine()->getRepository('ApiBundle:Activity')->find($id);
      if (empty($activity)) {
        $response =  new JsonResponse('activity not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($activity);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    
    }


     

}
