<?php

namespace ApiBundle\Controller;


use ApiBundle\Entity\ProductSurvey;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


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
     * Creates a new productSurvey entity.
     *
     * @Route("/new", name="productsurvey_new")
     * @Method({"POST"})
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
       
        $serializer = SerializerBuilder::create()->build();
        $productSurvey = $serializer->serialize($productSurvey, 'json');
    
        $response =  new Response($productSurvey, Response::HTTP_OK);
        return $response;
    }

    /**
     * Displays a form to edit an existing productSurvey entity.
     *
     * @Route("/{id}/edit", name="productsurvey_edit")
     * @Method({"PUT"})
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
