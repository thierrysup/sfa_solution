<?php

namespace ApiBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class ServiceGlobal 
{

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
        //consulter les performances des ressources périodique
    public function first(){
        $date_debut = new \Date();
        $date_fin = new \Date();
        $result = 'SELECT SUM(ps.quantity) AS qteRealiser,
        t.quantity
       FROM product_survey ps, survey su, product p, target t,
       quarter q, town v, sector s, region r, activity_user au
       WHERE 
           AND CAST( su.date_submit AS DATE) < :start_date  
           AND CAST( su.date_submit AS DATE) > :end_date  
           AND su.date_submit < :start_date
           AND su.date_submit > :end_date
           AND ps.survey_id = su.id
           AND q.id = su.quarter_id
           AND q.sector_id = s.id
           AND s.town_id = v.id
           AND v.region_id = r.id
           AND t.region_id = v.region_id
           AND su.user_id = au.user_id
           AND au.activity_id = p.activity_id
       GROUP BY r.id, p.id  
       ';
       $result = $this->em->getConnection()->prepare($result);
       $result->bindValue('start_date', $date_debut);
       $result->bindValue('end_date', $date_fin);
       $result->execute();
       $result = $result->fetchAll(); 

       $serializer = SerializerBuilder::create()->build();
       $result = $serializer->serialize($result, 'json');
 
     return $result; 
    
    }

    public function rapportPeriodeService($idUser,$idAct,$debut,$fin){

        $rapportUser = 'SELECT product.activity_id, tab1.date_submit ,survey.actor_name ,
       product.name AS product_name ,SUM(tab1.quantity) AS summRea,SUM(tab1.quantity_target) AS summObj,
                                            
       ((SUM(tab1.quantity)/SUM(tab1.quantity_target))*100) AS pourcentage
       FROM survey, product,(SELECT target.product_id AS product_id,product_survey.id AS product_survey_id,product_survey.quantity AS quantity, target.quantity AS quantity_target, product_survey.date_submit AS date_submit,product_survey.survey_id
                                                FROM target ,product_survey/*,survey*/
                                                WHERE product_survey.product_id=target.product_id
                                                /*AND  survey.user_id = :idUser
                                                AND product_survey.survey_id = survey.id*/
                                                AND target.start_date <= product_survey.date_submit
                                                AND target.end_date >= product_survey.date_submit
                                                AND product_survey.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
                                                AND product_survey.quantityIn IS NULL) AS tab1

        WHERE (survey.user_id = :idUser
        AND product.activity_id = :idAct
        AND survey.id = tab1.survey_id 
      /*  AND survey.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
        AND tab1.date_submit = survey.date_submit*/
        AND product.id = tab1.product_id)
       GROUP BY tab1.product_id ' ; 
      $rapportUser = $this->em->getConnection()->prepare($rapportUser);
      $rapportUser->bindValue('idUser', $idUser);
      $rapportUser->bindValue('idAct', $idAct);
      $rapportUser->bindValue('debut', $debut);
      $rapportUser->bindValue('fin', $fin); 
      $rapportUser->execute();
      $rapportUser = $rapportUser->fetchAll();
      return $rapportUser;
    }




    public function rapportPeriodeResourceService($debut,$fin){
        
                $rapportUser ='SELECT target.product_id AS product_id,
                product_survey.id AS product_survey_id,
                product_survey.quantity AS quantity,
                 target.quantity AS quantity_target, 
                 product_survey.date_submit AS date_submit,
                 product_survey.survey_id
                               FROM target ,product_survey
                                WHERE product_survey.product_id=target.product_id
                                AND target.start_date <= product_survey.date_submit
                                AND target.end_date >= product_survey.date_submit
                                AND product_survey.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
                                AND product_survey.quantityIn IS NULL' ; 
              $rapportUser = $this->em->getConnection()->prepare($rapportUser);
              $rapportUser->bindValue('debut', $debut);
              $rapportUser->bindValue('fin', $fin); 
              $rapportUser->execute();
              $rapportUser = $rapportUser->fetchAll();
              return $rapportUser;
            }
        

        /*     SET `quantite` = (
        CASE 
          WHEN `surcharge` < 1 THEN `quantite` + 1
          WHEN `surcharge` > 1 THEN `quantite` - 1
          ELSE quantite
        END
            ) */
      

    public function rapportUserPerfornancePeriodeService($idUser,$idAct,$debut,$fin){
       
       $rapportUser = 'SELECT product.activity_id, survey.date_submit ,survey.actor_name ,
       product.name AS product_name ,COUNT(product_survey.quantity) AS summRea,
       (SELECT
               SUM( CASE 
                    WHEN ((target.start_date <= CAST(:debut AS DATE)) 
                        AND (target.end_date <= CAST(:fin AS DATE))
                            AND (target.end_date > CAST(:debut AS DATE))) 
                    THEN target.quantity*DATEDIFF(   (DATE(target.end_date)), (CAST(:debut AS DATE)))
                    WHEN ((target.start_date > CAST(:debut AS DATE)) 
                        AND (target.end_date < CAST(:fin AS DATE))) 
                    THEN target.quantity*DATEDIFF(   (DATE(target.end_date)), (DATE(target.start_date)))
                    WHEN ((target.start_date > CAST(:debut AS DATE)) 
                        AND (target.end_date > CAST(:fin AS DATE))) 
                    THEN target.quantity*DATEDIFF(   (CAST(:fin AS DATE)), (DATE(target.start_date)))
                    WHEN ((target.start_date < CAST(:debut AS DATE)) 
                        AND (target.end_date > CAST(:fin AS DATE))) 
                    THEN target.quantity*DATEDIFF(   (CAST(:fin AS DATE)), (CAST(:debut AS DATE)))
                END )
            FROM target ,product AS p
            WHERE p.id= target.product_id
            AND p.id = product.id) AS sumTarget ,

            ((COUNT(product_survey.quantity)/(SELECT
                CASE 
                    WHEN ((target.start_date < CAST(:debut AS DATE)) 
                        AND (target.end_date < CAST(:fin AS DATE))
                            AND (target.end_date > CAST(:debut AS DATE))) 
                    THEN SUM(target.quantity*DATEDIFF(   (DATE(target.end_date)), (CAST(:debut AS DATE))))
                    WHEN ((target.start_date > CAST(:debut AS DATE)) 
                        AND (target.end_date < CAST(:fin AS DATE))) 
                    THEN SUM(target.quantity*DATEDIFF(   (DATE(target.end_date)), (DATE(target.start_date))))
                    WHEN ((target.start_date > CAST(:debut AS DATE)) 
                        AND (target.end_date > CAST(:fin AS DATE))) 
                    THEN SUM(target.quantity*DATEDIFF(   (CAST(:fin AS DATE)), (DATE(target.start_date))))
                    WHEN ((target.start_date < CAST(:debut AS DATE)) 
                        AND (target.end_date > CAST(:fin AS DATE))) 
                    THEN SUM(target.quantity*DATEDIFF(   (CAST(:fin AS DATE)), (CAST(:debut AS DATE))))
                END AS sumTarget
            FROM target ,product AS p
            WHERE p.id= target.product_id
            AND p.id = product.id)*100)) AS pourcentage,
        product_survey.quantityIn
       FROM survey, product_survey, product 
        WHERE (survey.user_id = :idUser
        AND product.activity_id = :idAct
        AND survey.id = product_survey.survey_id 
        AND product_survey.product_id = product.id 
        AND product_survey.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)       
        AND product_survey.quantityIn IS NULL)
      
       ' ;
      $rapportUser = $this->em->getConnection()->prepare($rapportUser);
      $rapportUser->bindValue('idUser', $idUser);
      $rapportUser->bindValue('idAct', $idAct);
      $rapportUser->bindValue('debut', $debut);
      $rapportUser->bindValue('fin', $fin);
      $rapportUser->execute();
      $rapportUser = $rapportUser->fetchAll();
      return $rapportUser;

    }


    public function rapportUserPerfornanceJourService($idUser,$idAct,$debut,$fin){
        
       $rapportUser = 'SELECT product.activity_id, survey.date_submit ,survey.actor_name ,
       product.name AS product_name ,COUNT(product_survey.quantity) AS summRea,
       ((COUNT(product_survey.quantity)/ (SELECT target.quantity
                                            FROM target ,product AS p
                                            WHERE p.id= target.product_id
                                            AND (DATE_SUB(DATE(NOW()), INTERVAL 1 DAY)) BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE) 
                                            AND p.id = product.id)*100)) AS pourcentage  ,
        product_survey.quantityIn
       FROM survey, product_survey, product 
        WHERE (survey.user_id = :idUser
        AND product.activity_id = :idAct
        AND survey.id = product_survey.survey_id 
        AND product_survey.product_id = product.id       
        AND product_survey.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
        AND product_survey.quantityIn IS NULL)
        GROUP BY product.id
       
      ' ;
      $rapportUser = $this->em->getConnection()->prepare($rapportUser);
      $rapportUser->bindValue('idUser', $idUser);
      $rapportUser->bindValue('idAct', $idAct);
      $rapportUser->bindValue('debut', $debut);
      $rapportUser->bindValue('fin', $fin);
      $rapportUser->execute();
      $rapportUser = $rapportUser->fetchAll();
      return $rapportUser;
   
    }

    public function rapportUserPerfornanceService($idUser,$idAct){
        
       $rapportUser = 'SELECT product.activity_id, survey.date_submit ,survey.actor_name ,
       product.name AS product_name ,COUNT(product_survey.quantity) AS summRea,
       ((COUNT(product_survey.quantity)/ (SELECT SUM(target.quantity)
                                            FROM target ,product AS p
                                            WHERE p.id= target.product_id 
                                            AND p.id = product.id)*100)) AS pourcentage  ,
        product_survey.quantityIn
       FROM survey, product_survey, product 
        WHERE (survey.user_id = :idUser
        AND product.activity_id = :idAct
        AND survey.id = product_survey.survey_id 
        AND product_survey.product_id = product.id       
        AND product_survey.quantityIn IS NULL)
        GROUP BY product.id
       
      ' ;
      $rapportUser = $this->em->getConnection()->prepare($rapportUser);
      $rapportUser->bindValue('idUser', $idUser);
      $rapportUser->bindValue('idAct', $idAct);
      $rapportUser->execute();
      $rapportUser = $rapportUser->fetchAll();
      return $rapportUser;
   
    }

 //   (COUNT(*)/SUM(target.quantity))*100 AS pourcentage,
   // SUM(target.quantity) AS summ ,

    public function rapportUneRessourceService($idUser,$idAct){
        $rapportUser = 'SELECT product.activity_id, survey.date_submit ,survey.actor_name ,
         product.name AS product_name ,COUNT(*) quantity,product_survey.quantityIn
        FROM survey, product_survey,product
        WHERE survey.user_id = :idUser
        AND product.id = product_survey.product_id
        AND product.activity_id = :idAct
        AND product_survey.survey_id = survey.id
        AND product_survey.quantityIn IS NULL
        GROUP BY product.id
        ' ;
        $rapportUser = $this->em->getConnection()->prepare($rapportUser);
        $rapportUser->bindValue('idUser', $idUser);
        $rapportUser->bindValue('idAct', $idAct);
        $rapportUser->execute();
        $rapportUser = $rapportUser->fetchAll(); 
    }

//rapport d'une ba sur une activité
    public function rapportsUserProduct($idUser, $idAct){
        $rapportUser = 'SELECT product.activity_id, survey.date_submit ,survey.actor_name ,
        product_survey.survey_id, product.name AS product_name ,product_survey.quantity,
         product_survey.product_id,
         product_survey.quantityIn

        FROM survey, product_survey,product
        WHERE survey.user_id = :idUser
        AND product.id = product_survey.product_id
        AND product.activity_id = :idAct
        AND product_survey.survey_id = survey.id
        AND product_survey.quantityIn IS NOT NULL
        ' ;
        $rapportUser = $this->em->getConnection()->prepare($rapportUser);
        $rapportUser->bindValue('idUser', $idUser);
        $rapportUser->bindValue('idAct', $idAct);
        $rapportUser->execute();
        $rapportUser = $rapportUser->fetchAll();
        return $rapportUser;  
    }

    public function hello(){
        
         return "hello world ....";  
     }



}