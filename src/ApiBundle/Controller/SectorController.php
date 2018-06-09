<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Sector;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Sector controller.
 *
 * @Route("/sector")
 */
class SectorController extends Controller
{
    /**
     * Lists all sector entities.
     *
     * @Route("/", name="sector_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $sectors = $em->getRepository('ApiBundle:Sector')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $sectors = $serializer->serialize($sectors, 'json');
        
                $response =  new Response($sectors, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Lists all sector entities.
     *
     * @Route("/page/{page}/{limit}", name="sector_page_index")
     * @Method("GET")
     */
    public function indexPageAction($page,$limit)
    {
        $em = $this->getDoctrine()->getManager();
        
                $sectors = $em->getRepository('ApiBundle:Sector')->findAll();
        
                $response =  new Response($this->paginate($sectors,$page,$limit), Response::HTTP_OK);        
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
     * Creates a new sector entity.
     *
     * @Route("/new", name="sector_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $sector = $serializer->deserialize($data,'ApiBundle\Entity\Sector', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $sector->setTown($this->getDoctrine()
        ->getRepository('ApiBundle:Town')
        ->findOneBy(['id' => $sector->getTown()->getId()]));
    
        // Add our quote to Doctrine so that it can be saved
        $em->persist($sector);
    
        // Save our country
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a sector entity.
     *
     * @Route("/{id}", name="sector_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $sector = $this->getDoctrine()
        ->getRepository('ApiBundle:Sector')
        ->findOneBy(['id' => $id]);
    
        if ($sector === null) {
            return new JsonResponse("sector not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $sector = $serializer->serialize($sector, 'json');
    
      $response =  new Response($sector, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing sector entity.
     *
     * @Route("/{id}/edit", name="sector_edit")
     * @Method({"PUT"})
     */
    public function editAction(Request $request, $id)
    {
        $sector = $this->getDoctrine()
        ->getRepository('ApiBundle:Sector')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to sector object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Sector', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $sector->setName($entity->getName());
        $sector->setDescription($entity->getDescription()); 
        $sector->setStatus($entity->getStatus());
        $sector->setCode($entity->getCode());
        $sector->setTown($this->getDoctrine()
        ->getRepository('ApiBundle:Town')
        ->findOneBy(['id' => $entity->getTown()->getId()]));
        
        // Save our sector
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
      return  $response;
    }

    /**
     * Deletes a sector entity.
     *
     * @Route("/{id}", name="sector_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $sector = $this->getDoctrine()->getRepository('ApiBundle:Sector')->find($id);
      if (empty($sector)) {
        $response =  new JsonResponse('sector not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($sector);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }

}
