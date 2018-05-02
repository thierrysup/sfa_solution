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
        $service = $this->get('service_requette');
        //$service = $this->get('logic_services');
        $user = 1;
        $act = 1;
        $product=2;
       //  $debut = new dateTime();
     //$fin = new DateTime();
        $debut = '2018-04-1';
        $fin = '2018-04-30';
     
        
            //   return new JsonResponse($service->rapportPeriodeByProduitService($user,$act,$debut,$fin,$product));
            //   return new JsonResponse($service->findResourceOByActivity($act));
                
              // return new JsonResponse($service->findSubordinateByUserIdAndActivityId($user,$act));
          //   return new JsonResponse($service->rapportPeriodeService($user,$act,$debut,$fin));
            
              return new JsonResponse($service->rapportUserPerfornanceService($user,$act));
          //   return new JsonResponse($service->rapportUserPerfornanceJourService($user,$act,$debut,$fin));
        //  return new JsonResponse($service->rapportUserPerfornancePeriodeService($user,$act,$debut,$fin));
                
          //return new JsonResponse($service->rapportPeriodeResourceService($debut,$fin));
          
          //   return new JsonResponse($service->rapportsUserProduct($user,$act));
            //    return new JsonResponse($service->first());

    }


     /**
     *
     * @Route("/logic", name="hello_journalier")
     * @Method({"GET"})
     */
     public function helloAction( )
     {
        $service = $this->get('logic_services');
         $user = 5;
         $act = 1;
         $product=2;
         $sector = 3;
        //  $debut = new dateTime();
      //$fin = new DateTime();
         $debut = '2018-04-1';
         $fin = '2018-04-30';
        return new JsonResponse($service->filterSurveyByUserAndActivityPeriodeSumService($act,$user,$debut,$fin));
     } 

}
