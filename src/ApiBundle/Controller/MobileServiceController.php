<?php
namespace ApiBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use ApiBundle\Entity\Survey;
use ApiBundle\Entity\ProductSurvey;


/**
 * service controller.
 *
 * @Route("/mobile")
 */
class MobileServiceController extends Controller
{

    /**
     *
     * @Route("/PostDynamiqueForm", name="post_mobile_DynamiqueForm")
     * @Method({"POST"})
     */
    public function postDynamiqueFormAction(Request $request)
    {
        $data = $request->getContent();

        $service = $this->get('mobile_services');
        $data = json_decode($data,true);
       // var_dump($data);
       // die();
        $survey = new Survey();
        $em = $this->getDoctrine()->getManager();
        
      $survey->setDateSubmit(date('Y-m-d', strtotime($data[0]['date_submit'])));
       $survey->setCommit( $data[0]['commit']); 
      $survey->setActorName($data[0]['actor_name']);
      $survey->setActorPhone($data[0]['actor_phone']);
      $survey->setStatus($data[0]['status']);
      $survey->setLattitude($data[0]['latitude']);
      $survey->setLongitude($data[0]['longitude']);
      /*  $survey->setQuarter($this->getDoctrine()
      ->getRepository('ApiBundle:Quarter')
      ->findOneBy(['id' => intval($data[0]['quarter_id'])]));
      $survey->setPos($this->getDoctrine()
      ->getRepository('ApiBundle:POS')
      ->findOneBy(['id' => intval($data[0]['pos_id'])]));
     $survey->setUser($this->getDoctrine()
      ->getRepository('AppBundle:User')
      ->findOneBy(['id' => intval($data[0]['user_id'])]));  */
      
    //  var_dump($survey);
    //  die();
      /*  $this->getDoctrine()
      ->getRepository( 'ApiBundle:Survey')->save($survey);
     /*  */  
      // Save our survey
      $em->persist($survey);
     $em->flush();
    //    die();
      //  $service = $this->get('mobile_services');
       
  
      //  return new JsonResponse($service->submitSurvey($data)); 
      return new JsonResponse("cool..."); 
   }
    
    /**
     *
     * @Route("/result", name="mobile_result_journalier")
     * @Method({"GET"})
     */
     public function rapportAction( )
     {
        $service = $this->get('mobile_services');
        $user = 1;
        $act = 1;
        $product=2;

        $debut = '2018-04-1';
        $fin = '2018-04-30';
            
        return new JsonResponse($service->findResourceOTerrainByActivityAndByManager($user,$act));

    }

    /**
     *
     * @Route("/Pos/{sectorId}", name="mobile_pos_sector")
     * @Method({"GET"})
     */
    public function posListAction( $sectorId )
    {
       $service = $this->get('mobile_services');
       return new JsonResponse($service->listPos(intval($sectorId)));
   } 
    

    /**
     *
     * @Route("/Quarter/{sectorId}", name="mobile_quarter_sector")
     * @Method({"GET"})
     */
    public function quarterListAction( $sectorId )
    {
       $service = $this->get('mobile_services');
       return new JsonResponse($service->listQuarter(intval($sectorId)));
   }

    /**
     *
     * @Route("/activity/{userId}", name="mobile_activity_journalier")
     * @Method({"GET"})
     */
     public function activityAction( $userId )
     {
        $service = $this->get('mobile_services');
        return new JsonResponse($service->getAct(intval($userId)));
    }


    /**
     *
     * @Route("/DynamiqueForm/{act}", name="mobile_DynamiqueForm")
     * @Method({"GET"})
     */
    public function DynamiqueFormAction( $act )
    {
       $service = $this->get('mobile_services');
       return new JsonResponse($service->getFormStructureByActivityId(intval($act)));
   }

     /**
     *
     * @Route("/report/{act}/{user}/{debut}/{fin}", name="mobile_report_service_list")
     * @Method({"GET"})
     */
     public function reportSupAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');
        $product=2;
      //  $act = 1; 
      //  $user = 6;
       //  = '2018-04-1';
       // $fin = '2018-04-30';
            
        return new JsonResponse($service->filterSurveyByUserAndActivityPeriodeSumService(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d H:i', strtotime($fin))));

    }

    /**
     *
     * @Route("/reportProduct/{act}/{user}/{debut}/{fin}", name="mobile_report_product_list")
     * @Method({"GET"})
     */
     public function reportProductSupAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');
       // $user =6;
       // $act = 1;
        $product=2;

       // $debut = '2018-04-1';
       // $fin = '2018-05-30';
            
        return new JsonResponse($service->filterSurveyByUserAndActivityPeriodeSumProduct(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }
    

    /**
     *
     * @Route("/db/{act}/{user}/{debut}/{fin}", name="mobile_db_product_list")
     * @Method({"GET"})
     */
     public function dbAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');
       // $user =6;
       // $act = 1;
        $product=2;

       // $debut = '2018-04-1';
       // $fin = '2018-05-30';
            
        return new JsonResponse($service->filterSurveyByUserAndActivityPeriodeSumGroupByDateService(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }
    

    /**
     *
     * @Route("/AllResourceQte0/{act}/{user}/{debut}/{fin}", name="mobile_AllRessourceQte_service_list")
     * @Method({"GET"})
     */
     public function serviceAllRessourceAndQuantityAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');

        $product=2;
    
        return new JsonResponse($service->DiagrammeRessourceAndQuantityForAllService(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }

    /**
     *
     * @Route("/AllResourceQte1/{act}/{user}/{debut}/{fin}", name="mobile_AllRessourceQte_product_list")
     * @Method({"GET"})
     */
     public function productAllRessourceAndQuantityAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');

        $product=2;
    
        return new JsonResponse($service->DiagrammeRessourceAndQuantityForAllProduct(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }


    /**
     *
     * @Route("/serviceRessourceQte/{act}/{user}/{debut}/{fin}", name="mobile_serviceRessourceQte_product_list")
     * @Method({"GET"})
     */
     public function serviceRessourceAndQuantityAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');
       // $user =6;
       // $act = 1;
        $product=2;

       // $debut = '2018-04-1';
       // $fin = '2018-05-30';

            
        return new JsonResponse($service->DiagrammeRessourceAndQuantityByService(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }


    /**
     *
     * @Route("/productRessourceQte/{act}/{user}/{debut}/{fin}", name="mobile_productRessourceQte_product_list")
     * @Method({"GET"})
     */
     public function productRessourceAndQuantityAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');
       // $user =6;
       // $act = 1;
        $product=2;

       // $debut = '2018-04-1';
       // $fin = '2018-05-30';

            
        return new JsonResponse($service->DiagrammeRessourceAndQuantityByProduct(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }

     /**
     *
     * @Route("/serviceDateQte/{act}/{user}/{debut}/{fin}", name="mobile_serviceDateQte_product_list")
     * @Method({"GET"})
     */
     public function serviceDateAndQuantityAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');
       // $user =6;
       // $act = 1;
        $product=2;

       // $debut = '2018-04-1';
       // $fin = '2018-05-30';

            
        return new JsonResponse($service->DiagrammeDateAndQuantityByService(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }

     /**
     *
     * @Route("/productDateQte/{act}/{user}/{debut}/{fin}", name="mobile_productDateQte_product_list")
     * @Method({"GET"})
     */
     public function productDateAndQuantityAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');
        return new JsonResponse($service->DiagrammeDateAndQuantityByProduct(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }

    /**
     *
     * @Route("/serviceQte/{act}/{user}/{debut}/{fin}", name="mobile_dbserviceQte_service_list")
     * @Method({"GET"})
     */
     public function serviceQteAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');
       // $user =6;
       // $act = 1;
        $product=2;

       // $debut = '2018-04-1';
       // $fin = '2018-05-30';
            
        return new JsonResponse($service->filterSurveyByActivityPeriodeSumService(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }

    /**
     *
     * @Route("/productQte/{act}/{user}/{debut}/{fin}", name="mobile_dbproductQte_product_list")
     * @Method({"GET"})
     */
     public function productQteAction($act,$user,$debut,$fin)
     {
        $service = $this->get('mobile_services');        
        return new JsonResponse($service->filterSurveyAndActivityPeriodeSumProduct(intval($act),intval($user),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }

    /**
     *
     * @Route("/baDetailSurveys/{ba}/{act}/{debut}/{fin}", name="mobile_baDetailSurveys_service")
     * @Method({"GET"})
     */
     public function getSurveyBaAction($ba,$act,$debut,$fin)
     {
        $service = $this->get('mobile_services');        
        return new JsonResponse($service->diagrammeSurveyPeriodeSumGroupByDateForOneBaService(intval($ba),intval($act),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }

    
    /**
     *
     * @Route("/baDetailSurveysProduct/{ba}/{act}/{debut}/{fin}", name="mobile_baDetailSurveys_product")
     * @Method({"GET"})
     */
    public function getSurveyBaProductAction($ba,$act,$debut,$fin)
    {
       $service = $this->get('mobile_services');        
       return new JsonResponse($service->diagrammeSurveyPeriodeSumGroupByDateForOneBaProduct(intval($ba),intval($act),
       date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

   }


    /**
     *
     * @Route("/baDetailListSurveys/{ba}/{act}/{debut}/{fin}", name="mobile_baDetailListSurveys_Service_list")
     * @Method({"GET"})
     */
     public function getSurveyListBaAction($ba,$act,$debut,$fin)
     {
        $service = $this->get('mobile_services');        
        return new JsonResponse($service->findDetailsSurveyByActivityIdPeriodeServiceOneUser(intval($ba),intval($act),
        date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

    }
//findDetailsSurveyByActivityIdPeriodeProductOneUser

    /**
     *          
     * @Route("/baDetailListSurveysProduct/{ba}/{act}/{debut}/{fin}", name="mobile_baDetailListSurveys_product_list")
     * @Method({"GET"})
     */
    public function getSurveyListBaProductAction($ba,$act,$debut,$fin)
    {
       $service = $this->get('mobile_services');        
       return new JsonResponse($service->findDetailsSurveyByActivityIdPeriodeProductOneUser(intval($ba),intval($act),
       date('Y-m-d', strtotime($debut)),date('Y-m-d', strtotime($fin))));

   }


    /**
     *
     * @Route("/ba/{act}/{user}", name="mobile_ba_act_user")
     * @Method({"GET"})
     */
     public function activityUserBaAction($act,$user )
     {
        $service = $this->get('mobile_services');
      // $user = 1;
      //  $act = 1;
        $product=2;

        $debut = '2018-04-1';
        $fin = '2018-04-30';
            
        return new JsonResponse($service->findResourceOTerrainByActivityAndByManager(intval($act),intval($user)));
    }

    


     /**
     *
     * @Route("/logic", name="mobile_hello_journalier")
     * @Method({"GET"})
     */
     public function helloAction( )
     {
        $service = $this->get('mobile_services');
         $user = 6 ;
         $act = 1;
         $product=2;
         $sector = 3;
         $debut = '2018-04-1';
         $fin = '2018-04-30';
        return new JsonResponse($service->filterSurveyByUserAndActivityPeriodeSumService($act,$user,$debut,$fin));
     } 

}
