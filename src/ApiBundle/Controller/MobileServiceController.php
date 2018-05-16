<?php
namespace ApiBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * service controller.
 *
 * @Route("mobile")
 */
class MobileServiceController extends Controller
{
    
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
     * @Route("/activity", name="mobile_activity_journalier")
     * @Method({"GET"})
     */
     public function activityAction( )
     {
        $service = $this->get('mobile_services');
        return new JsonResponse($service->getAct());
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
