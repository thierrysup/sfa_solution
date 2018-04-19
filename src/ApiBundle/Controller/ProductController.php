<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Product controller.
 *
 * @Route("product")
 */
class ProductController extends Controller
{
    /**
     * Lists all product entities.
     *
     * @Route("/", name="product_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $product = $em->getRepository('ApiBundle:Product')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $product = $serializer->serialize($product, 'json');
        
                $response =  new Response($product, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Creates a new product entity.
     *
     * @Route("/new", name="product_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $product = $serializer->deserialize($data,'ApiBundle\Entity\Product', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $product->setActivity($this->getDoctrine()
        ->getRepository('ApiBundle:Activity')
        ->findOneBy(['id' => $product->getActivity()->getId()]));
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($product);
    
        // Save our product
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a product entity.
     *
     * @Route("/{id}", name="product_show")
     * @Method("GET")
     */
    public function showAction(Product $product)
    {
        $product = $this->getDoctrine()
        ->getRepository('ApiBundle:Product')
        ->findOneBy(['id' => $id]);
    
        if ($product === null) {
            return new JsonResponse("product not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $product = $serializer->serialize($product, 'json');
    
      $response =  new Response($product, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Method({"PUT"})
     */
    public function editAction(Request $request, $id)
    {
    
        $product = $this->getDoctrine()
        ->getRepository('ApiBundle:Product')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to product object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Product', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $product->setName($entity->getName());
        $product->setGroupable($entity->getGroupable()); 
        $product->setQuantity($entity->getQuantity());
        $product->setFreeUse($entity->getFreeUse());
        $product->setStatus($entity->getStatus());
        $product->setDateCreate($entity->getDateCreate());
        $product->setActivity($this->getDoctrine()
        ->getRepository('ApiBundle:Activity')
        ->findOneBy(['id' => $entity->getActivity()->getId()]));


        // Save our product
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);


    }

    /**
     * Deletes a product entity.
     *
     * @Route("/{id}", name="product_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $product = $this->getDoctrine()->getRepository('ApiBundle:Product')->find($id);
      if (empty($product)) {
        $response =  new JsonResponse('product not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($product);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;    
    }

}
