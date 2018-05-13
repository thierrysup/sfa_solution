<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\Entreprise;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Entreprise controller.
 *
 * @Route("enterprise")
 */
class EntrepriseController extends Controller
{
    /**
     * Lists all entreprise entities.
     *
     * @Route("/", name="entreprise_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $entreprise = $em->getRepository('ApiBundle:Entreprise')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $entreprise = $serializer->serialize($entreprise, 'json');
        
                $response =  new Response($entreprise, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Lists all entreprise entities.
     *
     * @Route("/user/{id}", name="entreprise_user_index")
     * @Method("GET")
     */
    public function findEnterpriseByUserIdAction($id)
    {
        $service = $this->get('logic_services');
        $entreprises = $service->findEnterpriseByUserId($id);
        $results=[];

        foreach ($entreprises as $entreprise) {
            $results[]=array(
                'path' => $entreprise['name'],
                'enterprise_id' => $entreprise['id_en'],
                'currentUser_id' => $id,
                'title' => strtoupper($entreprise['name']),
                'ab' => strtoupper(substr($entreprise['name'],0,2)),
            );
        }

        return new JsonResponse($results,Response::HTTP_OK);
    }
    /**
     * find activities by enterprise for one user ...
     *
     * @Route("/{ent_id}/{user_id}", name="activities_enterprise_user_index")
     * @Method("GET")
     * @return void
     */
    public function findActivitiesByEnterpriseIdByUserIdAction($ent_id, $user_id) {
        $service = $this->get('logic_services');
        $listActivitiesDetail = $service->findActivitiesByEnterpriseIdByUserId($ent_id, $user_id);
        return new JsonResponse($listActivitiesDetail,Response::HTTP_OK);
    }

    /**
     * Creates a new entreprise entity.
     *
     * @Route("/new", name="entreprise_new")
     * @Method({"POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        $uploadedImage=$request->files->get('file');

        $serializer = SerializerBuilder::create()->build();
        $entreprise = $serializer->deserialize($data,'ApiBundle\Entity\Entreprise', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $entreprise->setActivity($this->getDoctrine()
        ->getRepository('ApiBundle:Activity')
        ->findOneBy(['id' => $entreprise->getActivity()->getId()]));

         $image=$uploadedImage;
         $imageName=md5(uniqid()).'.'.$image->guessExtension();
         $image->move($this->getParameter('image_directory'),$imageName);

         $entreprise->setLogoURL($imageName);
        // Add our quote to Doctrine so that it can be saved
        $em->persist($entreprise);
        // Save our entreprise
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a entreprise entity.
     *
     * @Route("/{id}", name="entreprise_show")
     * @Method("GET")
     */
    public function showAction( $id)
    {
        $entreprise = $this->getDoctrine()
        ->getRepository('ApiBundle:Entreprise')
        ->findOneBy(['id' => $id]);
    
        if ($entreprise === null) {
            return new JsonResponse("entreprise not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $entreprise = $serializer->serialize($entreprise, 'json');
    
      $response =  new Response($entreprise, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing entreprise entity.
     *
     * @Route("/{id}/edit", name="entreprise_edit")
     * @Method({"PUT"})
     */
    public function editAction(Request $request, $id)
    {
        $entreprise = $this->getDoctrine()
        ->getRepository('ApiBundle:Entreprise')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to entreprise object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\Entreprise', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $entreprise->setName($entity->getName());
        $entreprise->setAdresse($entity->getAdresse()); 
        $entreprise->setPobox($entity->getPobox());
        $entreprise->setPhone($entity->getPhone());
        $entreprise->setDescription($entity->getDescription());
        $entreprise->setColorStyle($entity->getColorStyle());
        $entreprise->setLogoURL($entity->getLogoURL());        
        $entreprise->setStatus($entity->getStatus());
       
        // Save our entreprise
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);

    }

    /**
     * Deletes a entreprise entity.
     *
     * @Route("/{id}", name="entreprise_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
       // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $entreprise = $this->getDoctrine()->getRepository('ApiBundle:Entreprise')->find($id);
      if (empty($entreprise)) {
        $response =  new JsonResponse('entreprise not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($entreprise);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;
    }

}
