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
 * @Route("/enterprise")
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
        
                $response =  new Response($entreprise,Response::HTTP_OK);        
                return $response;
    }

    /**
     * Lists all entreprise entities.
     *
     * @Route("/page/{page}/{limit}", name="entreprise_page_index")
     * @Method("GET")
     */
    public function indexPageAction($page,$limit)
    {
        $em = $this->getDoctrine()->getManager();
        
                $entreprise = $em->getRepository('ApiBundle:Entreprise')->findAll();
        
                $response =  new Response($this->paginate($entreprise,$page,$limit), Response::HTTP_OK);        
                return $response;
    }

    /**
     * Creates a new entreprise entity.
     *
     * @Route("/upload", name="entreprise_upload")
     * @Method({"POST"})
     */
    public function uploadAction(Request $request)
    {
        $data = $request->files->get('Image');
       
         $image=$data;
         $imageName=md5(uniqid()).'.'.$image->guessExtension();
         $image->move($this->getParameter('image_directory'),$imageName);

     $response =  new JsonResponse($imageName, Response::HTTP_OK);
     
     return $response;
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

        

        $serializer = SerializerBuilder::create()->build();
        $entreprise = $serializer->deserialize($data,'ApiBundle\Entity\Entreprise', 'json');

        $data = json_decode(utf8_decode($data),true);
        // Get the Doctrine service and manager
        $em = $this->getDoctrine()->getManager();
        //var_dump($data['name']);
        //die();
        //va
          $entreprise->setLogoURL($data['logoURL']);
          $entreprise->setColorStyle($data['colorStyle']);
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

        $data = json_decode(utf8_decode($data), true);
        
        $entreprise->setName($entity->getName());
        $entreprise->setAdresse($entity->getAdresse()); 
        $entreprise->setPobox($entity->getPobox());
        $entreprise->setPhone($entity->getPhone());
        $entreprise->setDescription($entity->getDescription());
        $entreprise->setColorStyle($data['colorStyle']);
        $entreprise->setLogoURL($data['logoURL']);        
        $entreprise->setStatus($entity->getStatus());
       
        // Save our entreprise
         $em->flush();
      $response =  new JsonResponse('It\'s probably been updated', Response::HTTP_OK);
        return $response;
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



    /**
     *
     *         Logic controlller inside one Custom Account
     *  G Raport -D Report - DashBoard - Analyse - Manage -Mapping
     *
     */


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
     * Details grouping by report function
     *
     *@Route("/sum/grouping/date/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}/{page}/{limit}", name="surveys_details_grouping_index")
     * @Method("GET")
     * @return void
     */
    public function filterSurveyByUserAndActivityPeriodeSumGroupByDateServiceAction($id_act,$id_user,$start_date,$type_activity,$end_date,$page,$limit){
        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {

            $listSurveys = $service->filterSurveyByUserAndActivityPeriodeSumGroupByDateService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        } else {
            $listSurveys = $service->filterSurveyByUserAndActivityPeriodeSumGroupByDateProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }
        /* var_dump($id_act.'-----'.$id_user.'-----'.$start_date.'-----'.$end_date.'-----'.$page.'-----'.$limi);
        die(); */
        return new Response($this->paginate($listSurveys,$page,$limit),Response::HTTP_OK);
    }

    /**
     * global report function
     *
     *@Route("/sum/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}/{page}/{limit}", name="surveys_global_sum_index")
     * @Method("GET")
     * @return void
     */
    public function filterSurveyByUserAndActivityPeriodeSumServiceAction($id_act,$id_user,$start_date,$end_date,$type_activity,$page,$limit){
        $service = $this->get('logic_services');
        
        if (intval($type_activity) === 0) {
            $listSurveys = $service->filterSurveyByUserAndActivityPeriodeSumService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $listSurveys = $service->filterSurveyByUserAndActivityPeriodeSumProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }

        return new Response($this->paginate($listSurveys,$page,$limit),Response::HTTP_OK);
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
     * global report function
     *
     *@Route("/dashBoard/town/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}", name="surveys_dashboard_town_index")
     * @Method("GET")
     * @return void
     */
    public function getDashBoardTownAction($id_act,$id_user,$start_date,$end_date,$type_activity){
        
        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {
            $resumeDatas = $service->getDashBoardTownResumeDataService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $resumeDatas = $service->getDashBoardTownResumeDataProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($resumeDatas, 'json');

        return new Response($data,Response::HTTP_OK);
    }

     /**
     * global report function
     *
     *@Route("/dashBoard/region/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}", name="surveys_dashboard_region_index")
     * @Method("GET")
     * @return void
     */
    public function getDashBoardRegionAction($id_act,$id_user,$start_date,$end_date,$type_activity){
        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {
            $resumeDatas = $service->getDashBoardRegionResumeDataService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $resumeDatas = $service->getDashBoardRegionResumeDataProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($resumeDatas, 'json');

        return new Response($data,Response::HTTP_OK);
    }
     /**
     * global report function
     *
     *@Route("/dashBoard/country/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}", name="surveys_dashboard_country_index")
     * @Method("GET")
     * @return void
     */
    public function getDashBoardCountryAction($id_act,$id_user,$start_date,$end_date,$type_activity){
        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {
            $resumeDatas = $service->getDashBoardCountryResumeDataService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $resumeDatas = $service->getDashBoardCountryResumeDataProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($resumeDatas, 'json');

        return new Response($data,Response::HTTP_OK);
    }
     /**
     * global report function
     *
     *@Route("/dashBoard/sector/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}", name="surveys_dashboard_sector_index")
     * @Method("GET")
     * @return void
     */
    public function getDashBoardSectorAction($id_act,$id_user,$start_date,$end_date,$type_activity){
        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {
            $resumeDatas = $service->getDashBoardSectorResumeDataService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $resumeDatas = $service->getDashBoardSectorResumeDataProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($resumeDatas, 'json');

        return new Response($data,Response::HTTP_OK);
    }
     /**
     * global report function
     *
     *@Route("/dashBoard/quarter/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}", name="surveys_dashboard_quarter_index")
     * @Method("GET")
     * @return void
     */
    public function getDashBoardQuarterAction($id_act,$id_user,$start_date,$end_date,$type_activity){
        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {
            $resumeDatas = $service->getDashBoardQuarterResumeDataService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $resumeDatas = $service->getDashBoardQuarterResumeDataProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($resumeDatas, 'json');

        return new Response($data,Response::HTTP_OK);
    }
     /**
     * global report function
     *
     *@Route("/dashBoard/basic/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}", name="surveys_dashboard_basic_index")
     * @Method("GET")
     * @return void
     */
    public function getDashBoardBasicAction($id_act,$id_user,$start_date,$end_date,$type_activity){
        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {
            $resumeDatas = $service->getDashBoardQuarterResumeDataService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $resumeDatas = $service->getDashBoardPosResumeDataProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($resumeDatas, 'json');

        return new Response($data,Response::HTTP_OK);
    }

    /**
     * global report function
     *
     *@Route("/analyse/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}/{page}/{limit}", name="surveys_analyse_index")
     * @Method("GET")
     * @return void
     */
    public function getAnalyseAction($id_act,$id_user,$start_date,$end_date,$type_activity,$page,$limit){

        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {
            $resumeDatas = $service->getAnalyseResumeDataService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $resumeDatas = $service->getAnalyseResumeDataProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }

        return new Response($this->paginate($resumeDatas,$page,$limit),Response::HTTP_OK);
    }


    /**
     * global Diagramms function
     *
     *@Route("/diagrammsglobal/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}", name="surveys_diagramms_index")
     * @Method("GET")
     * @return void
     */
    public function getGlobalDiagrammsAction($id_act,$id_user,$start_date,$end_date,$type_activity){

        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {
            $resumeDatas = $service->filterSurveyByUserAndActivityPeriodeSumDiagrammsService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $resumeDatas = $service->filterSurveyByUserAndActivityPeriodeSumDiagrammsProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }
        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($resumeDatas, 'json');
        return new Response($data,Response::HTTP_OK);
    }


    /**
     * global Diagramms function
     *
     *@Route("/diagrammsgroupingbydate/{id_act}/{id_user}/{start_date}/{end_date}/{type_activity}", name="surveys_diagramms_date_index")
     * @Method("GET")
     * @return void
     */
    public function getGlobalDiagrammsGroupingAction($id_act,$id_user,$start_date,$end_date,$type_activity){

        $service = $this->get('logic_services');
        if (intval($type_activity) === 0) {
            $resumeDatas = $service->filterSurveyByUserAndActivityPeriodeSumGroupByDateDiagramsService(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }else{
            $resumeDatas = $service->filterSurveyByUserAndActivityPeriodeSumGroupByDateDiagramsProduct(intval($id_act),intval($id_user),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));
        }

        $serializer = $this->get('jms_serializer');
        $data = $serializer->serialize($resumeDatas, 'json');
        return new Response($data,Response::HTTP_OK);
    }


    /**
     * global Manage function
     *
     *@Route("/manage/{id_act}/{start_date}/{end_date}/{page}/{limit}", name="surveys_manage_index")
     * @Method("GET")
     * @return void
     */
    public function pointingResourceAction($id_act,$start_date,$end_date,$page,$limit){

        $service = $this->get('logic_services');

        $resumeDatas = $service->pointingResource(intval($id_act),date('Y-m-d',strtotime($start_date)),date('Y-m-d',strtotime($end_date)));

        return new Response($this->paginate($resumeDatas,$page,$limit),Response::HTTP_OK);
    }

}
