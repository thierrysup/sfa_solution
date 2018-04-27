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
       // $service = $this->get('service_requette');
        $service = $this->get('logic_services');
        $user = 1;
        $act = 1;
       //  $debut = new dateTime();
     //$fin = new DateTime();
        $debut = '2018-04-15';
        $fin = '2018-04-20';
     
     //   return new JsonResponse($service->findResourceOByActivity($act));
        
        return new JsonResponse($service->findSubordinateByUserIdAndActivityId($user,$act));
    // return new JsonResponse($service->rapportPeriodeService($user,$act,$debut,$fin));
     
     // return new JsonResponse($service->rapportUserPerfornanceService($user,$act));
   //  return new JsonResponse($service->rapportUserPerfornanceJourService($user,$act,$debut,$fin));
 //  return new JsonResponse($service->rapportUserPerfornancePeriodeService($user,$act,$debut,$fin));
         
  //return new JsonResponse($service->rapportPeriodeResourceService($debut,$fin));
  
   //   return new JsonResponse($service->rapportsUserProduct($user,$act));
    //    return new JsonResponse($service->first());
    
    
   /*  
    
     $rapportUser = 'SELECT product.activity_id, survey.date_submit ,survey.actor_name ,
     product.name AS product_name ,product.quantity,
     SUM(target.quantity) AS summ ,
      product_survey.quantityIn
    FROM survey
    INNER JOIN product_survey ON product_survey.survey_id = survey.id
    INNER JOIN product ON product_survey.product_id = product.id
    INNER JOIN target ON product.id = target.product_id 
    WHERE (survey.user_id = :idUser
    AND product.activity_id = :idAct
    AND product_survey.quantityIn IS NULL )
    GROUP BY product.id
    ' ;
    $rapportUser = $this->em->getConnection()->prepare($rapportUser);
    $rapportUser->bindValue('idUser', $idUser);
    $rapportUser->bindValue('idAct', $idAct);
    $rapportUser->execute();
    $rapportUser = $rapportUser->fetchAll();
    return $rapportUser; */
    
    
    } 


     /**
     *
     * @Route("/hello", name="hello_journalier")
     * @Method({"GET"})
     */
     public function helloAction( )
     {
        $service = $this->get('service_requette');
        $user = 1;
        return new JsonResponse($service->hello());
     } 

}
