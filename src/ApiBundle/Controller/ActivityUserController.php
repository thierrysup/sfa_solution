<?php

namespace ApiBundle\Controller;

use ApiBundle\Entity\ActivityUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use JMS\Serializer\SerializerBuilder;

/**
 * Activityuser controller.
 *
 * @Route("activityuser")
 */
class ActivityUserController extends Controller
{
    /**
     * Lists all activityUser entities.
     *
     * @Route("/", name="activityuser_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
                $activityUser = $em->getRepository('ApiBundle:activityUser')->findAll();
        
                $serializer = SerializerBuilder::create()->build();
                $activityUser = $serializer->serialize($activityUser, 'json');
        
                $response =  new Response($activityUser, Response::HTTP_OK);        
                return $response;
    }

    /**
     * Creates a new activityUser entity.
     *
     * @Route("/new", name="activityuser_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $data = $request->getContent();
        
        $serializer = SerializerBuilder::create()->build();
        $activityUser = $serializer->deserialize($data,'ApiBundle\Entity\ActivityUser', 'json');
        
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        $activityUser->setPos($this->getDoctrine()
        ->getRepository('ApiBundle:Pos')
        ->findOneBy(['id' => $activityUser->getPos()->getId()]));

        $activityUser->setTown($this->getDoctrine()
        ->getRepository('ApiBundle:Town')
        ->findOneBy(['id' => $activityUser->getTown()->getId()]));

        $activityUser->setQuarter($this->getDoctrine()
        ->getRepository('ApiBundle:Quarter')
        ->findOneBy(['id' => $activityUser->getQuarter()->getId()]));

        $activityUser->setSector($this->getDoctrine()
        ->getRepository('ApiBundle:Sector')
        ->findOneBy(['id' => $activityUser->getSector()->getId()]));

        $activityUser->setRegion($this->getDoctrine()
        ->getRepository('ApiBundle:Region')
        ->findOneBy(['id' => $activityUser->getRegion()->getId()]));

        $activityUser->setCountry($this->getDoctrine()
        ->getRepository('ApiBundle:Country')
        ->findOneBy(['id' => $activityUser->getCountry()->getId()]));

        $activityUser->setRole($this->getDoctrine()
        ->getRepository('ApiBundle:Role')
        ->findOneBy(['id' => $activityUser->getRole()->getId()]));

        $activityUser->setUser($this->getDoctrine()
        ->getRepository('ApiBundle:User')
        ->findOneBy(['id' => $activityUser->getUser()->getId()]));

        $activityUser->setActivity($this->getDoctrine()
        ->getRepository('ApiBundle:Activity')
        ->findOneBy(['id' => $activityUser->getActivity()->getId()]));


        // Add our quote to Doctrine so that it can be saved
        $em->persist($activityUser);
    
        // Save our activityUser
        $em->flush();
     $response =  new JsonResponse('It\'s probably been saved', Response::HTTP_OK);
     
     return $response;
    }

    /**
     * Finds and displays a activityUser entity.
     *
     * @Route("/{id}", name="activityuser_show")
     * @Method("GET")
     */
    public function showAction($id)
    {
        $activityUser = $this->getDoctrine()
        ->getRepository('ApiBundle:ActivityUser')
        ->findOneBy(['id' => $id]);
    
        if ($activityUser === null) {
            return new JsonResponse("activityUser not found", Response::HTTP_NOT_FOUND);
        }
        $serializer = SerializerBuilder::create()->build();
        $activityUser = $serializer->serialize($activityUser, 'json');
    
      $response =  new Response($activityUser, Response::HTTP_OK);
      return $response;
    }

    /**
     * Displays a form to edit an existing activityUser entity.
     *
     * @Route("/{id}/edit", name="activityuser_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        
        $activityUser = $this->getDoctrine()
        ->getRepository('ApiBundle:ActivityUser')
        ->findOneBy(['id' => $id]); 

        $data = $request->getContent();

        //now we want to deserialize data request to activityUser object ...
        $serializer = SerializerBuilder::create()->build();
        $entity = $serializer->deserialize($data,'ApiBundle\Entity\ActivityUser', 'json');
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        
        $activityUser->setEditAuth($entity->getEditAuth());
        $activityUser->setCreateAuth($entity->getCreateAuth()); 
        $activityUser->setDeleteAuth($entity->getDeleteAuth());
        $activityUser->setDateSubmit($entity->getDateSubmit());
        $activityUser->setStatus($entity->getStatus());
        $activityUser->setZoneInfluence($entity->getZoneInfluence());
        $activityUser->setMobility($entity->getMobility());
        $activityUser->setPos($this->getDoctrine()
        ->getRepository('ApiBundle:Pos')
        ->findOneBy(['id' => $activityUser->getPos()->getId()]));

        $activityUser->setTown($this->getDoctrine()
        ->getRepository('ApiBundle:Town')
        ->findOneBy(['id' => $activityUser->getTown()->getId()]));

        $activityUser->setQuarter($this->getDoctrine()
        ->getRepository('ApiBundle:Quarter')
        ->findOneBy(['id' => $activityUser->getQuarter()->getId()]));

        $activityUser->setSector($this->getDoctrine()
        ->getRepository('ApiBundle:Sector')
        ->findOneBy(['id' => $activityUser->getSector()->getId()]));

        $activityUser->setRegion($this->getDoctrine()
        ->getRepository('ApiBundle:Region')
        ->findOneBy(['id' => $activityUser->getRegion()->getId()]));

        $activityUser->setCountry($this->getDoctrine()
        ->getRepository('ApiBundle:Country')
        ->findOneBy(['id' => $activityUser->getCountry()->getId()]));

        $activityUser->setRole($this->getDoctrine()
        ->getRepository('ApiBundle:Role')
        ->findOneBy(['id' => $activityUser->getRole()->getId()]));

        $activityUser->setUser($this->getDoctrine()
        ->getRepository('ApiBundle:User')
        ->findOneBy(['id' => $activityUser->getUser()->getId()]));

        $activityUser->setActivity($this->getDoctrine()
        ->getRepository('ApiBundle:Activity')
        ->findOneBy(['id' => $activityUser->getActivity()->getId()]));
        
        // Save our activityUser
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
 

    }

    /**
     * Deletes a activityUser entity.
     *
     * @Route("/{id}", name="activityuser_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        // Get the Doctrine service and manager
      $em = $this->getDoctrine()->getManager();
      $activityUser = $this->getDoctrine()->getRepository('ApiBundle:ActivityUser')->find($id);
      if (empty($activityUser)) {
        $response =  new JsonResponse('activityUser not found', Response::HTTP_NOT_FOUND);
        return $response;
       }
       else {
        $em->remove($activityUser);
        $em->flush();
       }
      $response =  new JsonResponse('deleted successfully', Response::HTTP_OK);
      return $response;     }

    /**
     * Creates a form to delete a activityUser entity.
     *
     * @param ActivityUser $activityUser The activityUser entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ActivityUser $activityUser)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('activityuser_delete', array('id' => $activityUser->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
