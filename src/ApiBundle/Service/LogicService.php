<?php

namespace ApiBundle\Service;

use Doctrine\ORM\EntityManager;

class LogicService {

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function submitSurvey($surveyEntity,$productSurvey){

            $SURVEY_QUERY = 'INSERT INTO survey VALUES (NULL,:quarter_id,:pos_id,:date_submit,:post,:actorName,:actorPhone,:statusS,:latitude,:longitude,:userId)';
            $survey = $this->em->getConnection()->prepare($SURVEY_QUERY);
                $survey->bindValue('quarter_id', $surveyEntity->getQuarter()->getId());
                $survey->bindValue('pos_id', $surveyEntity->getPOS()->getId());
                $survey->bindValue('date_submit', $surveyEntity->getDateSubmit());
                $survey->bindValue('post', $surveyEntity->getCommit());
                $survey->bindValue('actorName', $surveyEntity->getActorName());
                $survey->bindValue('actorPhone', $surveyEntity->getActorPhone());
                $survey->bindValue('statusS', $surveyEntity->getStatus());
                $survey->bindValue('latitude', $surveyEntity->getLattitude());
                $survey->bindValue('longitude', $surveyEntity->getLongitude());
                $survey->bindValue('userId', $surveyEntity->getUser()->getId());
            $survey->execute();
            $survey = $survey->fetch();
            $PRODUCT_SURVEY_QUERY = 'INSERT INTO product_survey VALUES (NULL,:product_id,:survey_id,:quantity,:date_submit,:quantityIn,:statusS,:baseLine)';
            $product_survey = $this->em->getConnection()->prepare($PRODUCT_SURVEY_QUERY);
                $product_survey->bindValue('product_id', $productSurvey->getProduct()->getId());
                $product_survey->bindValue('survey_id', $survey['id']);
                $product_survey->bindValue('quantity', $productSurvey->getQuantity());
                $product_survey->bindValue('date_submit', $productSurvey->getDateSubmit());
                $product_survey->bindValue('quantityIn', $productSurvey->getQuantityIn());
                $product_survey->bindValue('actorPhone', $surveyEntity->getActorPhone());
                $product_survey->bindValue('statusS', $surveyEntity->getStatus());
            $product_survey->execute();
            $product_survey = $product_survey->fetch();
        return $product_survey;
    }
    public function findSubordinateByManagerId($id){
        $MANAGER_QUERY = 'SELECT u.username username,a.name act_name,r.name role_name
            FROM user_manager um ,activity a,user u,activity_user ua,role r
            WHERE (um.manager_id = :id 
              AND um.subordinate_id = u.id 
              AND um.activity_id = a.id 
              AND ua.activity_id = a.id
              AND ua.user_id = u.id
              AND ua.role_id = r.id
              AND um.status = 1
              AND u.enabled = 1
              AND a.status = 1
              AND r.status = 1
              AND ua.status = 1)
            GROUP BY a.id';
        $results = $this->em->getConnection()->prepare($MANAGER_QUERY);
            $results->bindValue('id', $id);
            $results->execute();
        $results = $results->fetchAll();

        // Manual serialization to structure our custom service to API
        $dataSend=array();
        foreach ($results as $result) {
            $dataSend[] = array(
                'subordinate'=>$result['username'],
                'activity' =>$result['act_name'],
                'role'=>$result['role_name']
            ); 
        }
        return $dataSend;
    }

    public function findSubordinateByUserIdAndActivityId($id,$idAct){
        $MANAGER_QUERY = 'SELECT u.username username,a.name act_name,a.id activity_id
            FROM user_manager um ,activity a,user u,activity_user ua
            WHERE (um.manager_id = :id 
              AND um.subordinate_id = u.id 
              AND um.activity_id = a.id 
              AND ua.activity_id = a.id
              AND ua.user_id = u.id
        
              AND a.id = :idAct
              AND um.status = 1
              AND u.enabled = 1
              AND a.status = 1
           
              AND ua.status = 1)
            GROUP BY a.id';
        $results = $this->em->getConnection()->prepare($MANAGER_QUERY);
            $results->bindValue('id', $id);
            $results->bindValue('idAct', $idAct);
            $results->execute();
        $results = $results->fetchAll();

        // Manual serialization to structure our custom service to API
        $dataSend=array();
        foreach ($results as $result) {
            $dataSend[] = array(
                'subordinate'=>$result['username'],
                'activity' =>$result['act_name'],
                'activity_id' =>$result['activity_id']
            ); 
        }
        return $dataSend;
    }
    
    public function findGeneralUsersManage(){
        $MANAGER_QUERY = 'SELECT u.username username,a.id activity_id,(SELECT user.username FROM user WHERE user.enable = 1 AND um.manager_id = user.id) manager_name,a.name act_name,r.name role_name
            FROM user_manager um ,activity a,user u,activity_user ua,role r
            WHERE (um.subordinate_id = u.id 
              AND um.activity_id = a.id 
              AND ua.activity_id = a.id
              AND ua.user_id = u.id
              AND ua.role_id = r.id
              AND um.status = 1
              AND u.enabled = 1
              AND a.status = 1
              AND r.status = 1
              AND ua.status = 1)
            GROUP BY a.id ,um.manager_id';
        $results = $this->em->getConnection()->prepare($MANAGER_QUERY);
            $results->execute();
        $results = $results->fetchAll();

        // Manual serialization to structure our custom service to API
        $dataSend=array();
        foreach ($results as $result) {
            $dataSend[] = array(
                'manager'=>$result['manager_name'],
                'subordinate'=>$result['username'],
                'activity' =>$result['act_name'],
                'act_id' =>$result['activity_id'],
                'role'=>$result['role_name']
            ); 
        }
        return $dataSend;
    }

    public function findActivityByUserId($userId){
        $QUERY = 'SELECT u.username username,ua.zoneInfluence zoneInfluence,ua.mobility mobility,ua.editAuth editE,ua.createAuth createE,ua.delete deleteE ,a.name act_name,a.id act_id,r.name role_name,
                       (CASE ua.zoneInfluence 
                       WHEN 1 THEN ua.pos_id
                       WHEN 2 THEN ua.quarter_id
                       WHEN 3 THEN ua.zone_id
                       WHEN 4 THEN ua.town_id
                       WHEN 5 THEN ua.region_id
                       ELSE ua.country_id
                       END AS zone_influence_id)

            FROM activity a,user u,activity_user ua,role r
            WHERE (u.id = :id
              AND ua.activity_id = a.id
              AND ua.user_id = u.id
              AND ua.role_id = r.id
              AND u.enabled = 1
              AND a.status = 1
              AND r.status = 1
              AND ua.status = 1)';
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('id', $userId);
            $results->execute();
        $results = $results->fetchAll();

        // Manual serialization to structure our custom service to API
        $dataSend=array();
        foreach ($results as $result) {
            $dataSend[] = array(
                'act_id'=>$result['act_id'],
                'activity' =>$result['act_name'],
                'edit' =>$result['editE'],
                'create' =>$result['createE'],
                'delete' =>$result['deleteE'],
                'mobility' =>$result['mobility'],
                'zone_influence' =>$result['zoneInfluence'],
                'id_influence_zone' =>$result['zone_influence_id'],
                'role'=>$result['role_name']
            );
        }
        return $dataSend;
    }
//liste ba sur une activité
    public function findResourceOTerrainByActivity($idAct){
        $MANAGER_QUERYS = 'SELECT DISTINCT(u.id) , u.username 
        FROM user_manager um ,activity a,user u,activity_user ua
        WHERE ua.user_id = u.id
          AND ua.activity_id = :idAct
          AND ua.mobility = 1
          AND um.activity_id = ua.activity_id
          AND u.id IN (SELECT user.id 
                                        FROM  user 
                                        WHERE  user.id  IN (SELECT Um.subordinate_id idu FROM user_manager Um WHERE Um.activity_id = :idAct )
                                )
          AND u.id NOT IN (SELECT user.id 
                                        FROM  user 
                                        WHERE  user.id  IN (SELECT Um.manager_id idu FROM user_manager Um WHERE Um.activity_id = :idAct )
                                )
          AND a.id = :idAct         
          AND u.enabled = 1
          AND a.status = 1
          AND ua.status = 1
          AND um.status = 1

          
        ';
    $results = $this->em->getConnection()->prepare($MANAGER_QUERYS);
        $results->bindValue('idAct', $idAct);
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

}