<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Survey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Survey controller.
 *
 * @Route("survey")
 */
class SurveyController extends Controller
{
    /**
     * Lists all survey entities.
     *
     * @Route("/", name="survey_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $survey = $em->getRepository('ApiBundle:Survey')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $survey = $serializer->serialize($survey, 'json');
        
                $response =  new Response($survey, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Creates a new survey entity.
     *
     * @Route("/new", name="survey_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Survey', 'json');

        
        $em = $this->getDoctrine()->getManager();
       // $entity->setDateSubmit(date('y-m-d', strtotime($data['date_submit'])));
        if ($entity->getQuarter() !== NULL) {
            $entity->setQuarter($this->getDoctrine()
        ->getRepository('ApiBundle:Quarter')
        ->findOneBy(['id' => $entity->getQuarter()->getId()]));
        } else {
            $entity->setPos($this->getDoctrine()
            ->getRepository('ApiBundle:POS')
            ->findOneBy(['id' => $entity->getPos()->getId()]));
        }
        
        $entity->setUser($this->getDoctrine()
        ->getRepository('AppBundle:User')
        ->findOneBy(['id' => $entity->getUser()->getId()]));
        //var_dump($entity);
        //die();

        // Add our quote to Doctrine so that it can be saved
        $em->persist($entity);
    
        // Save our Survey
        $em->flush();
     $response =  new JsonResponse(array('id' => $entity->getId()), Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a survey entity.
     *
     * @Route("/{id}", name="survey_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $survey = $this->getDoctrine()
        ->getRepository('ApiBundle:Survey')
        ->findOneBy(['id' => $id]);
    
        if ($survey === null) {
            return new JsonResponse("survey not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $survey = $serializer->serialize($survey, 'json');
    
      $response =  new Response($survey, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing survey entity.
     *
     * @Route("/{id}/edit", name="survey_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        
        $survey = $this->getDoctrine()
        ->getRepository('ApiBundle:Survey')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to survey object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Survey', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $survey->setDateSubmit($entity->getDateSubmit());
        $survey->setCommit($entity->getCommit()); 
        $survey->setActorName($entity->getActorName());
        $survey->setActorPhone($entity->getActorPhone());
        $survey->setStatus($entity->getStatus());
        $survey->setLattitude($entity->getLattitude());
        $survey->setLongitude($entity->getLongitude());
        $survey->setQuarter($this->getDoctrine()
        ->getRepository('ApiBundle:Quarter')
        ->findOneBy(['id' => $entity->getQuarter()->getId()]));
        $survey->setPos($this->getDoctrine()
        ->getRepository('ApiBundle:POS')
        ->findOneBy(['id' => $entity->getPos()->getId()]));


        // Save our survey
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);


    }

    /**
     * Deletes a survey entity.
     *
     * @Route("/{id}", name="survey_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Survey $survey)
    {
        
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $survey = $this->getDoctrine()->getRepository('ApiBundle:Survey')->find($id);
      if (empty($survey)) {
        $response =  new JsonResponse('survey not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($survey);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }

}
