<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Quarter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Quarter controller.
 *
 * @Route("/quarter")
 */
class QuarterController extends Controller
{
    /**
     * Lists all quarter entities.
     *
     * @Route("/", name="quarter_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $quarters = $em->getRepository('ApiBundle:Quarter')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $quarters = $serializer->serialize($quarters, 'json');
        
                $response =  new Response($quarters, Response::HTTP_OK);        
                return $response;
    }
    /**
     * Lists all quarter entities.
     *
     * @Route("/page/{page}/{limit}", name="quarter_page_index")
     * @Method("GET")
     */
    public function indexPageAction($page,$limit)
    {
        $em = $this->getDoctrine()->getManager();
        
                $quarters = $em->getRepository('ApiBundle:Quarter')->findAll();
        
                $response =  new Response($this->paginate($quarters,$page,$limit), Response::HTTP_OK);        
                return $response;
    }

    public function paginate($array,$page,$limit){
        $pager=$this->get('knp_paginator');
        $paginated=$pager->paginate($array,$page, $limit);

        $resItems = ($paginated->getTotalItemCount()%$limit);
        $numPages = (int)($paginated->getTotalItemCount()/$limit);
        $totalPages = ($resItems === 0) ? $numPages : $numPages + 1 ;

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize([
            'page'=> intval($paginated->getCurrentPageNumber()),
            'relativesTotal'=> count($paginated->getItems()),
            'globalTotal'=> $paginated->getTotalItemCount(),
            'numberOfPages'  => $totalPages ,
            'limit'  => intval($limit),
            'items' => $paginated->getItems()
        ], 'json');
        return $data;
    }

    /**
     * Creates a new quarter entity.
     *
     * @Route("/new", name="quarter_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $Quarter = $serializer->deserialize($data,'ApiBundle\Entity\Quarter', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $Quarter->setSector($this->getDoctrine()
        ->getRepository('ApiBundle:Sector')
        ->findOneBy(['id' => $Quarter->getSector()->getId()]));
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($Quarter);
    
        // Save our country
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a quarter entity.
     *
     * @Route("/{id}", name="quarter_show")
     * @Method("GET")
     */
    public function showAction( $id)
    {
        $Quarter = $this->getDoctrine()
        ->getRepository('ApiBundle:Quarter')
        ->findOneBy(['id' => $id]);
    
        if ($Quarter === null) {
            return new JsonResponse("Quarter not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $Quarter = $serializer->serialize($Quarter, 'json');
    
      $response =  new Response($Quarter, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing quarter entity.
     *
     * @Route("/{id}/edit", name="quarter_edit")
     * @Method({"PUT"})
     */
    public function editAction(Request $request,$id)
    {
        $Quarter = $this->getDoctrine()
        ->getRepository('ApiBundle:Quarter')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to Quarter object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Quarter', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $Quarter->setName($entity->getName());
        $Quarter->setDescription($entity->getDescription()); 
        $Quarter->setStatus($entity->getStatus());
        $Quarter->setSector($this->getDoctrine()
        ->getRepository('ApiBundle:Sector')
        ->findOneBy(['id' => $entity->getSector()->getId()]));
        
        // Save our Quarter
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
          return $response;
    }

    /**
     * Deletes a quarter entity.
     *
     * @Route("/{id}", name="quarter_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $Quarter = $this->getDoctrine()->getRepository('ApiBundle:Quarter')->find($id);
      if (empty($Quarter)) {
        $response =  new JsonResponse('Quarter not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($Quarter);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }


    
}
