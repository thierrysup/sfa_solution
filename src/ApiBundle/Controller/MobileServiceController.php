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
     * @Route("/report/", name="mobile_report_service_list")
     * @Method({"GET"})
     */
     public function reportSupAction()
     {
        $service = $this->get('mobile_services');
        $product=2;
        $act = 1; 
        $user = 1;
        $debut = '2018-04-1';
        $fin = '2018-04-30';
            
        return new JsonResponse($service->filterSurveyByUserAndActivityPeriodeSumService($act,$user,$debut,$fin));

    }

    /**
     *
     * @Route("/reportProduct/", name="mobile_report_product_list")
     * @Method({"GET"})
     */
     public function reportProductSupAction()
     {
        $service = $this->get('mobile_services');
        $user =1;
        $act = 1;
        $product=2;

        $debut = '2018-04-1';
        $fin = '2018-04-30';
            
        return new JsonResponse($service->filterSurveyByUserAndActivityPeriodeSumProduct(intval($act),intval($user),$debut,$fin));

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
            
        return new JsonResponse($service->findResourceOTerrainByActivityAndByManager($act,$user));
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
