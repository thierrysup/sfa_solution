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
 * @Route("requette")
 */
class ServiceController extends Controller
{
    
    /**
     *
     * @Route("/result", name="result_journalier")
     * @Method({"GET"})
     */
     public function rapportAction( )
     {
        $service = $this->get('logic_services');
        $user = 1;
        $act = 1;
        $product=2;

        $debut = '2018-04-1';
        $fin = '2018-04-30';
            
        return new JsonResponse($service->findResourceOTerrainByActivityAndByManager($user,$act));

    }
    
    /**
     *
     * @Route("/activity", name="activity_journalier")
     * @Method({"GET"})
     */
     public function activityAction( )
     {
        $service = $this->get('logic_services');
        return new JsonResponse($service->getAct());
    }


    /**
     *
     * @Route("/ba/{act}/{user}", name="ba_act_user")
     * @Method({"GET"})
     */
     public function activityUserBaAction($act,$user )
     {
        $service = $this->get('logic_services');
      // $user = 1;
      //  $act = 1;
        $product=2;

        $debut = '2018-04-1';
        $fin = '2018-04-30';
            
        return new JsonResponse($service->findResourceOTerrainByActivityAndByManager($act,$user));

    }

     /**
     *
     * @Route("/report/{act}/{user}", name="report_list")
     * @Method({"GET"})
     */
     public function reportSupAction($act,$user )
     {
        $service = $this->get('logic_services');
      // $user = 1;
      //  $act = 1;
        $product=2;

        $debut = '2018-04-1';
        $fin = '2018-04-30';
            
        return new JsonResponse($service->filterSurveyByUserAndActivityPeriodeSumService($act,$user,$debut,$fin));

    }

     /**
     *
     * @Route("/logic", name="hello_journalier")
     * @Method({"GET"})
     */
     public function helloAction( )
     {
        $service = $this->get('logic_services');
         $user = 6;
         $act = 1;
         $product=2;
         $sector = 3;
         $debut = '2018-04-1';
         $fin = '2018-04-30';
        return new JsonResponse($service->filterSurveyByUserAndActivityPeriodeSumProduct($act,$user,$debut,$fin));
     } 

}
