<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\POS;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Po controller.
 *
 * @Route("/pos")
 */
class POSController extends Controller
{
    /**
     * Lists all pO entities.
     *
     * @Route("/", name="pos_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $pos = $em->getRepository('ApiBundle:POS')->findAll();
        
                 $serializer = SerializerBuilder::create()->build();
                 $pos = $serializer->serialize($pos, 'json');
        
                $response =  new Response($pos, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Lists all pO entities.
     *
     * @Route("/page/{page}/{limit}", name="pos_page_index")
     * @Method("GET")
     */
    public function indexPageAction($page,$limit)
    {
        $em = $this->getDoctrine()->getManager();
        
                $pos = $em->getRepository('ApiBundle:POS')->findAll();
        
                $response =  new Response($this->paginate($pos,$page,$limit), Response::HTTP_OK);        
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
     * Creates a new pO entity.
     *
     * @Route("/new", name="pos_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $pos = $serializer->deserialize($data,'ApiBundle\Entity\POS', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $pos->setQuarter($this->getDoctrine()
        ->getRepository('ApiBundle:Quarter')
        ->findOneBy(['id' => $pos->getQuarter()->getId()]));
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($pos);
    
        // Save our country
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a pO entity.
     *
     * @Route("/{id}", name="pos_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $pos = $this->getDoctrine()
        ->getRepository('ApiBundle:POS')
        ->findOneBy(['id' => $id]);
    
        if ($pos === null) {
            return new JsonResponse("pos not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $pos = $serializer->serialize($pos, 'json');
    
      $response =  new Response($pos, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing pO entity.
     *
     * @Route("/{id}/edit", name="pos_edit")
     * @Method({"PUT"})
     */
    public function editAction(Request $request, $id)
    {
        $pos = $this->getDoctrine()
        ->getRepository('ApiBundle:POS')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to pos object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\POS', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $pos->setName($entity->getName());
        $pos->setAdresse($entity->getAdresse());
        $pos->setPhone($entity->getPhone()); 
        $pos->setDescription($entity->getDescription());  
        $pos->setStatus($entity->getStatus());
        $pos->setQuarter($this->getDoctrine()
        ->getRepository('ApiBundle:Quarter')
        ->findOneBy(['id' => $entity->getQuarter()->getId()]));
        
        // Save our pos
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
      return  $response;
    }

    /**
     * Deletes a pO entity.
     *
     * @Route("/{id}", name="pos_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $pos = $this->getDoctrine()->getRepository('ApiBundle:POS')->find($id);
      if (empty($pos)) {
        $response =  new JsonResponse('pos not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($pos);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }

}
