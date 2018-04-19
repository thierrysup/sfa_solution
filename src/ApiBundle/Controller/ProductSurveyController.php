<?php

namespace ApiBundle\Controller;


use ApiBundle\Entity\ProductSurvey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use ApiBundle\Entity\Region;
use ApiBundle\Entity\Activity;
use ApiBundle\Entity\Country;
use ApiBundle\Entity\Entreprise;
use ApiBundle\Entity\POS;
use ApiBundle\Entity\Product;
//use ApiBundle\Entity\ProductSurvey;
use ApiBundle\Entity\Quarter;
use ApiBundle\Entity\Role;
use ApiBundle\Entity\Sector;
use ApiBundle\Entity\Survey;
use ApiBundle\Entity\Target;
use ApiBundle\Entity\Town;

use JMS\Serializer\SerializerBuilder;

/**
 * Productsurvey controller.
 *
 * @Route("productsurvey")
 */
class ProductSurveyController extends Controller
{
    /**
     * Lists all productSurvey entities.
     *
     * @Route("/", name="productsurvey_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $productSurvey = $em->getRepository('ApiBundle:ProductSurvey')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $productSurvey = $serializer->serialize($productSurvey, 'json');
        
                $response =  new Response($productSurvey, Response::HTTP_OK);        
                return $response;
    }

    /**
     * resultat requette raport journalier.
     *
     * @Route("/result/{id}", name="result_journalier")
     * @Method({"GET"})
     */
/*      public function rapportAction( $id)
     {
        $service = $this->get('service_requette');
            
    

             return new JsonResponse($service->first($id));
     } */


    /**
     * Creates a new productSurvey entity.
     *
     * @Route("/new", name="productsurvey_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        
        
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $productSurvey = $serializer->deserialize($data,'ApiBundle\Entity\ProductSurvey', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $productSurvey->setProduct($this->getDoctrine()
        ->getRepository('ApiBundle:Product')
        ->findOneBy(['id' => $productSurvey->getProduct()->getId()]));
        $productSurvey->setSurvey($this->getDoctrine()
        ->getRepository('ApiBundle:Survey')
        ->findOneBy(['id' => $productSurvey->getSurvey()->getId()]));

        // Add our quote to Doctrine so that it can be saved
        $em->persist($productSurvey);
    
        // Save our productSurvey
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response; 

    }

    /**
     * Finds and displays a productSurvey entity.
     *
     * @Route("/{id}", name="productsurvey_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $productSurvey = $this->getDoctrine()
        ->getRepository('ApiBundle:ProductSurvey')
        ->findOneBy(['id' => $id]);
    
        if ($productSurvey === null) {
            return new JsonResponse("productSurvey not found", Response::HTTP_NOT_FOUND);
        }
        $date_debut = new \Date();
        $date_fin = new \Date();
        $result = 'SELECT SUM(ps.quantity) AS qteRealiser,
        t.quantity
       FROM Product_survey ps, survey su, product p, target t,
       quarter q, town v, sector s, region r, product_survey au
       WHERE 
           AND CAST( su.date_submit AS DATE) < :start_date  
           AND CAST( su.date_submit AS DATE) > :end_date  
           AND su.date_submit < :start_date
           AND su.date_submit > :end_date
           AND ps.survey_id = su.id
           AND q.id = su.quarter_id
           AND q.sector_id = s.id
           AND s.town_id = v.id
           AND v.region_id = r.id
           AND t.region_id = v.region_id
           AND su.user_id = au.user_id
           AND au.activity_id = p.activity_id
       GROUP BY r.id, p.id,  
       ';
       $em = $this->getDoctrine()->getManager();
       $result = $em->getConnection()->prepare($result);
       $result->bindValue('start_date', $date_debut);
       $result->bindValue('end_date', $date_fin);
       $result->execute();
       $result = $result->fetchAll();

        $serializer = SerializerBuilder::create()->build();
        $productSurvey = $serializer->serialize($productSurvey, 'json');
    
      $response =  new Response($result, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing productSurvey entity.
     *
     * @Route("/{id}/edit", name="productsurvey_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $productSurvey = $this->getDoctrine()
        ->getRepository('ApiBundle:ProductSurvey')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to productSurvey object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\ProductSurvey', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $productSurvey->setQuantity($entity->getQuantity());
        $productSurvey->setDateSubmit($entity->getDateSubmit()); 
        $productSurvey->setQuantityIn($entity->getQuantityIn());
        $productSurvey->setStatus($entity->getStatus());
        $productSurvey->setProduct($this->getDoctrine()
        ->getRepository('ApiBundle:Product')
        ->findOneBy(['id' => $entity->getProduct()->getId()]));
        $productSurvey->setSurvey($this->getDoctrine()
        ->getRepository('ApiBundle:Survey')
        ->findOneBy(['id' => $entity->getSurvey()->getId()]));


        // Save our productSurvey
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);

    }

    /**
     * Deletes a productSurvey entity.
     *
     * @Route("/{id}", name="productsurvey_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $productSurvey = $this->getDoctrine()->getRepository('ApiBundle:ProductSurvey')->find($id);
      if (empty($productSurvey)) {
        $response =  new JsonResponse('productSurvey not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($productSurvey);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response; 
       }

}
