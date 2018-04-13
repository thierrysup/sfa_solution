<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Article;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Article controller.
 *
 * @Route("api/article")
 */
class ArticleController extends Controller
{
    /**
     * Lists all article entities.
     *
     * @Route("/", name="article_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $articles = $em->getRepository('ApiBundle:Article')->findAll();
        //$data = $request->getContent();
        $serializer = SerializerBuilder::create()->build();
        $articles = $serializer->serialize($articles, 'json');

        $response =  new Response($articles, Response::HTTP_OK);
        

        return $response;
    }

    /**
     * Creates a new article entity.
     *
     * @Route("/new", name="article_new")
     * @Method({"POST"})
     * @return Response
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
            
            $serializer = SerializerBuilder::create()->build();
            $article = $serializer->deserialize($data,'ApiBundle\Entity\Article', 'json');
            
            // Get the Doctrine service and manager
            $em = $this->getDoctrine()->getManager();
            $article->setClient($this->getDoctrine()
            ->getRepository('ApiBundle:Client')
            ->findOneBy(['id' => $article->getClient()->getId()]));
        
            // Add our quote to Doctrine so that it can be saved
            $em->persist($article);
        
            // Save our article
            $em->flush();
         $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
         

         return $response;

    }

    /**
     * Finds and displays a article entity.
     *
     * @Route("/{id}", name="article_show")
     * @Method({"GET"})
     */
    public function showAction($id)
    {
        $article = $this->getDoctrine()
        ->getRepository('ApiBundle:Article')
        ->findOneBy(['id' => $id]);


    
        if ($article === null) {
            return new JsonResponse("article not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $article = $serializer->serialize($article, 'json');
    
      $response =  new Response($article, Response::HTTP_OK);
      

      return $response;
    }

    /**
     * Displays a form to edit an existing article entity.
     *
     * @Route("/{id}/edit", name="article_edit")
     * @Method({"PUT"})
     */
    public function editAction(Request $request,$id)
    {
        $article = $this->getDoctrine()
        ->getRepository('ApiBundle:Article')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to article object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Article', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $article->setIntitule($entity->getIntitule());
        $article->setNombre($entity->getNombre());
        $article->setClient($this->getDoctrine()
        ->getRepository('ApiBundle:Client')
        ->findOneBy(['id' => $entity->getClient()->getId()]));
        
        // Save our article
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
     

      return $response;
    }

    /**
     * Deletes a article entity.
     *
     * @Route("/{id}", name="article_delete")
     * @Method("DELETE")
     */
    public function deleteAction($id)
    {
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $article = $this->getDoctrine()->getRepository('ApiBundle:Article')->find($id);
      if (empty($article)) {
        $response =  new JsonResponse('article not found', Response::HTTP_NOT_FOUND);
        

        return $response;
       }
       else {
        $em->remove($article);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      

      return $response;
    }
}
