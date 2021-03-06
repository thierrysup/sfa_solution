<?php

namespace ApiBundle\Service;

use Doctrine\ORM\EntityManager;
use ApiBundle\dto\UserDto;
use ApiBundle\Entity\Survey;

class MobileService {

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    
    /**
     * Save a survey and product_survey
     *
     * @param [Survey] $surveyEntity
     * @param [Array<ProductSurvey>] $productSurveys
     * @return void
     */
    public function submitSurvey($data){
     
       $survey = new Survey();
   
            
           $posId =$survey[0]['pos_id'];
           $dateSubmit = $survey[0]['date_submit'];
           $commit = $survey[0]['commit'];
           $actorName = $survey[0]['actor_name'];
           $actorPhone = $survey[0]['actor_phone'];
           $status = $survey[0]['status'];
           $latitude = $survey[0]['latitude'];
           $longitude = $survey[0]['longitude'];
           $userId = $survey[0]['user_id'];
           var_dump("helo...");
           die();
           
  
    }


    /**
     * List of quarter for one resource in one activity
     *
     * @param [int] $id
     * @return void
     */
    public function listQuarter($id){
        $QUERY = 'SELECT *
            FROM quarter q
            WHERE q.sector_id = :id 
            AND q.status = 1
             ';
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('id', $id);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

     /**
     * List of pos for one resource in one activity
     *
     * @param [int] $id
     * @return void
     */
    public function listPos($id){
        $QUERY = 'SELECT pos.id,pos.name
            FROM quarter q, p_o_s pos
            WHERE q.sector_id = :id 
            AND pos.quarter_id = q.id
            AND q.status = 1
            AND pos.status = 1
             ';
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('id', $id);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }


    /**
     * List of subalterns for one manager in all activities
     *
     * @param [int] $id
     * @return void
     */
    public function findSubordinateByManagerId($id){
        $QUERY = 'SELECT DISTINCT u.id id_user,u.username ,a.name act_name, a.id act_id
            FROM user_manager um ,activity a,user u,activity_user ua
            WHERE (um.manager_id = :id 
              AND um.subordinate_id = u.id 
              AND um.activity_id = a.id 
              AND u.id =ua.user_id
              AND um.status = 1
              AND u.enabled = 1
              AND a.status = 1
              AND ua.status = 1)';
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('id', $id);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }
    /**
     * find different enterprise who act one user
     *
     * @param [int] $id
     * @return void
     */
    public function findEnterpriseByUserId($id){
        $QUERY = 'SELECT DISTINCT en.id id_en,en.name ,en.adresse adress
            FROM entreprise en ,activity a,activity_user ua
            WHERE (ua.user_id = :id 
              AND ua.activity_id = a.id 
              AND a.entreprise_id = en.id 
              AND en.status = 1
              AND a.status = 1
              AND ua.status = 1)';
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('id', $id);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

    public function findActivitiesByEnterpriseIdByUserId($id_ent, $id_user){
        $QUERY = 'SELECT DISTINCT en.id id_en,en.name en_name,en.colorStyle en_color,en.logoURL en_logo,ua.user_id user_id,en.adresse adress,a.id id_act, a.name name_act,a.typeActivity type_act,r.id r_id,r.name name_role,a.start_date start_date,a.end_date end_date
            FROM entreprise en ,activity a,activity_user ua, role r
            WHERE (ua.user_id = :id 
              AND ua.activity_id = a.id 
              AND ua.role_id =r.id
              AND en.id = :id_ent
              AND a.entreprise_id = en.id 
              AND r.status =1
              AND en.status = 1
              AND a.status = 1
              AND ua.status = 1)';
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('id', $id_user);
            $results->bindValue('id_ent', $id_ent);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }


    /**
     *  List of subalterns for one manager in one activity
     *
     * @param [int] $id
     * @param [int] $idAct
     * @return void
     */
    public function findSubordinateByUserIdAndActivityId($id,$idAct){
        $QUERY = 'SELECT DISTINCT u.id,u.username username,a.name act_name,a.id activity_id
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
              AND ua.status = 1)';
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('id', $id);
            $results->bindValue('idAct', $idAct);
            $results->execute();
        $results = $results->fetchAll();
        return $results;
    }
    
    /**
     * Lits of Managers and corresponding subalterns for each activity
     *
     * @return void
     */
    public function findGeneralUsersManage(){
        $QUERY = 'SELECT u.id id,u.username username,a.id activity_id,(SELECT user.username FROM user WHERE user.enabled = 1 AND um.manager_id = user.id) manager_name,a.name act_name,r.name role_name
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
              AND ua.status = 1)';
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }


    /**
     * List of activities for this user
     *
     * @param [int] $userId
     * @return void
     */
    public function findActivityByUserId($userId){
              $QUERY = 'SELECT ua.zoneInfluence zoneInfluence,
              ua.mobility mobility,ua.editAuth editE,ua.createAuth createE,
              ua.deleteAuth deleteE ,a.id act_id,
                    (CASE ua.zoneInfluence
                    WHEN 1 THEN ua.pos_id
                    WHEN 2 THEN ua.quarter_id
                    WHEN 3 THEN ua.sector_id
                    WHEN 4 THEN ua.town_id
                    WHEN 5 THEN ua.region_id
                    ELSE ua.country_id
                    END ) AS zone_influence_id

            FROM activity_user ua,activity a
            WHERE (ua.activity_id = a.id
                AND ua.user_id = :id
                AND a.status = 1
                AND ua.status = 1)';
              
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('id', $userId);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

    /**
     * List of user who pushing survey data
     *
     * @param [type] $idAct
     * @return void
     */
    public function findResourceOTerrainByActivity($idAct){
        $QUERY = 'SELECT DISTINCT(u.id) , u.username 
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
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('idAct', $idAct);
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    public function getContentIds($idAct,$userId){
        $operationalResources = $this->findResourceOTerrainByActivityAndByManager($idAct,$userId);
        $ids=[];
        foreach ($operationalResources as $operationalResource) {
            $ids[]=$operationalResource['id'];
        }
        return $ids;
    }

    public function getAct($userId){
      
        $QUERY = 'SELECT u.username,u.phone AS phoneUser,au.sector_id,a.id,a.name nameAct, a.typeActivity ,a.start_date,a.end_date,e.logoURL,e.name nameEntreprise,e.adresse,e.pobox,e.phone
        FROM activity a,entreprise e, activity_user au, user u
        WHERE a.status = 1
        AND au.status = 1
        AND e.status = 1
        AND u.id = :idUser
        AND au.user_id = u.id
        AND au.activity_id = a.id
        AND e.id = a.entreprise_id
        ';
        $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('idUser', $userId);
        $results->execute();
        $results = $results->fetchAll();

    return $results;
    }


    /**
     * Get operational resources for one manager
     *
     * @param [int] $idAct
     * @param [int] $userId
     * @return void
     */
    public function findResourceOTerrainByActivityAndByManager($idAct,$userId){
        $QUERY = 'SELECT DISTINCT(u.id) , u.username , s.description , u.phone
        FROM user_manager um ,activity a,user u,activity_user ua,sector s
        WHERE ua.user_id = u.id
          AND ua.activity_id = :idAct
          AND ua.mobility = 1
          AND um.activity_id = ua.activity_id
          AND u.id IN (SELECT user.id 
                                        FROM  user 
                                        WHERE  user.id  IN (SELECT Um.subordinate_id idu FROM user_manager Um WHERE Um.activity_id = :idAct AND Um.manager_id = :idUser )
                                )
          AND u.id NOT IN (SELECT user.id 
                                        FROM  user 
                                        WHERE  user.id  IN (SELECT Um.manager_id idu FROM user_manager Um WHERE Um.activity_id = :idAct)
                                )
          AND a.id = :idAct
          AND s.id = ua.sector_id
          AND u.enabled = 1
          AND a.status = 1
          AND ua.status = 1
          AND um.status = 1
        ';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('idAct', $idAct);
        $results->bindValue('idUser', $userId);
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    /**
     * Give all pos in one sector
     *
     * @param [int] $idSector
     * @return void
     */
    public function findPOSBySectorId($idSector){
        $QUERY = 'SELECT q.id quarter_id,q.name quarter_name,p.id pos_id,p.name pos_name
        FROM quarter q,p_o_s p
        WHERE ( q.sector_id = :idSector AND p.quarter_id = q.id)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('idSector', $idSector);
        $results->execute();
    $results = $results->fetchAll();

            return $results;
    }
    /**
     * Give all quarter in one sector
     *
     * @param [type] $idSector
     * @return void
     */
    public function findQuarterBySectorId($idSector){
        $QUERY = 'SELECT q.id quarter_id,q.name quarter_name
        FROM quarter q
            WHERE ( q.sector_id = :idSector)';
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idSector', $idSector);
            $results->execute();
        $results = $results->fetchAll();
        
       return $results;
    }
    /**
     * Give all survey who had been pushed and using specific target of this area
     *
     * @param [int] $idAct
     * @param [int] $userId
     * @return void
     */
    public function findSurveyByRelativeTargetAndActivityIdAndUserIdService($idAct,$userId){


        $QUERY = 'SELECT product.activity_id,product.name,SUM(tab1.quantity) AS summRea,SUM(tab1.quantity_target) AS summObj,
        ((SUM(tab1.quantity)/SUM(tab1.quantity_target))*100) AS pourcentage
        FROM survey, product,(SELECT target.product_id AS product_id,ps.id AS product_survey_id,ps.quantity AS quantity, target.quantity AS quantity_target, ps.date_submit AS date_submit,ps.survey_id
                                                    FROM target ,product_survey ps,product p,survey su,quarter q,activity_user au,sector s,town t
                                                    WHERE ps.product_id=target.product_id
                                                    AND su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND su.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.quantityIn IS NULL) AS tab1

            WHERE (survey.user_id = :idUser
            AND product.activity_id = :idAct
            AND survey.id = tab1.survey_id 
            AND product.id = tab1.product_id)
        GROUP BY tab1.product_id ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->bindValue('idUser', $userId);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

    /**
     * Give all summary survey whom had been pushed categorize by product and user 
     *
     * @param [int] $idAct
     * @return void
     */
    public function findSummarySurveyByActivityIdService($idAct){

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id/* ,target.region_id */,SUM(ps.quantity) AS quantity,SUM(target.quantity) AS quantity_target, ps.date_submit AS date_submit/*,su.date_submit date,ps.survey_id */,su.user_id user_id
                                                    FROM target ,product p,product_survey ps,survey su,quarter q,activity_user au,sector s,town t
                                                    WHERE su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND su.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.quantityIn IS NULL GROUP BY product_id,user_id,ps.date_submit
        ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

    /**
     * Give all detail survey whom had been pushed 
     *
     * @param [int] $idAct
     * @return void
     */
    public function findDetailsSurveyByActivityIdService($idAct){

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id,target.region_id ,ps.quantity AS quantity,target.quantity AS quantity_target, ps.date_submit AS date_submit,su.date_submit date,ps.survey_id,su.quarter_id quarter_id,su.user_id user_id
                                                    FROM target ,product p,product_survey ps,survey su,quarter q,activity_user au,sector s,town t
                                                    WHERE su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND su.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.quantityIn IS NULL 
        ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

   

    /**
     * Give all detail survey whom had been pushed to the office personal
     * related by self influence zone
     *
     * @param [int] $idAct
     * @return void
     */
    public function findDetailsSurveyByActivityIdByEmployeIdService($idAct,$userId){

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id,target.region_id ,ps.quantity AS quantity,target.quantity AS quantity_target, ps.date_submit AS date_submit,su.date_submit date,ps.survey_id,su.user_id user_id,su.quarter_id quarter_id
                                                    FROM target ,product p,product_survey ps,survey su,quarter q,activity_user au,sector s,town t
                                                    WHERE su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND su.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND target.region_id = t.region_id
                                                    AND su.user_id = :idUser
                                                    AND au.activity_id = :idAct
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.quantityIn IS NULL 
        ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->bindValue('idUser', $userId);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

    /**
     * Give all survey relative by influence area of this login user for one activity
     *
     * @param [int] $idAct
     * @param [int] $user
     * @return void
     */
    public function filterSurveyByUserAndActivityService($idAct,$userId){
        $user = $this->em->getRepository('AppBundle:User')->find($userId);
        $regionId = $user->getRegionId($idAct);
        $results = $this->findDetailsSurveyByActivityIdService($idAct);
        //var_dump($results);
        //die();
        $data =[];
            
        
           foreach ($results as $result) {
            if ($regionId === -1 || $regionId === 0) {
                if (in_array($result['region_id'],$user->getListOfIdReferenceAreaByActivityId($idAct))) {
                    $data[]= $this->initTableService($result);
                }
            } else {
                //var_dump((intval($result['region_id']) === $regionId) && ($user->getZoneInfluence($idAct) === 5));
                //die();
                if ((intval($result['region_id']) === $regionId) && ($user->getZoneInfluence($idAct) === 5)) {
                    $data[]= $this->initTableService($result);
                }else {
                    if ($user->getZoneInfluence($idAct) === 4) {
                        if (in_array($this->em->getRepository('ApiBundle:Quarter')->find($result['quarter_id'])->getSector()->getId(),$user->getListOfIdReferenceAreaByActivityId($idAct))) {
                            $data[]= $this->initTableService($result);
                        }
                    } else {
                      //  var_dump(in_array($result['user_id'],$this->getContentIds($idAct,$userId)));
                       // die();
                        if ((in_array($result['quarter_id'],$user->getListOfIdReferenceAreaByActivityId($idAct)))&&(in_array($result['user_id'],$this->getContentIds($idAct,$userId)))) {
                            $data[]= $this->initTableService($result);
                        }
                    }
                    
                }
            }
        }  
        return $data;
    }

     /**
     * Give all detail survey whom had been pushed 
     *
     * @param [int] $idAct
     * @return void
     */
    public function findDetailsSurveyByActivityIdPeriodeService($idAct,$startDate,$endDate){

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id, p.name AS product_name,target.region_id ,ps.quantity AS quantity,
        target.quantity AS quantity_target, ps.date_submit AS date_submit, user.phone AS phone,
        user.username As nameBa, su.date_submit date,ps.survey_id,su.quarter_id quarter_id,su.user_id user_id
                                                    FROM target ,product p,product_survey ps,survey su,quarter q,activity_user au,sector s,town t,user
                                                    WHERE su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND user.id = su.user_id
                                                    AND su.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
                                                    AND ps.quantityIn IS NULL 
        ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->bindValue('fin', $endDate);
            $results->bindValue('debut', $startDate);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }



     /**
     * Give all detail survey whom had been pushed 
     *
     *@param [int] $idBa
     * @param [int] $idAct
     * @return void
     */
     public function findDetailsSurveyByActivityIdPeriodeServiceOneUser($idBa,$idAct,$startDate,$endDate){
     

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id, p.name AS product_name,target.region_id ,ps.quantity AS quantity,
                target.quantity AS quantity_target,ps.id AS productSurveyId, ps.date_submit AS date_submit, ps.commit, user.phone AS phone,
                user.username As nameBa, su.date_submit date,ps.baseline,ps.status,ps.survey_id AS survey,su.quarter_id quarter_id,su.user_id user_id
                                                            FROM target ,product p,product_survey ps,survey su,quarter q,activity_user au,sector s,town t,user
                                                            WHERE su.id = ps.survey_id
                                                            AND p.id = ps.product_id
                                                            AND target.product_id =p.id
                                                            AND su.user_id = au.user_id
                                                            AND user.id = su.user_id
                                                            AND user.id = :idBa
                                                            AND su.quarter_id = q.id
                                                            AND q.sector_id =s.id
                                                            AND s.id = au.sector_id
                                                            AND s.town_id = t.id
                                                            AND target.region_id = t.region_id
                                                            AND au.activity_id = :idAct
                                                            AND target.start_date <= ps.date_submit
                                                            AND target.end_date >= ps.date_submit
                                                            AND ps.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
                                                            AND ps.quantityIn IS NULL 
                                                            ORDER BY ps.date_submit
                 ' ; 
                $results = $this->em->getConnection()->prepare($QUERY);
                $results->bindValue('idBa', $idBa);
                    $results->bindValue('idAct', $idAct);
                    $results->bindValue('fin', $endDate);
                    $results->bindValue('debut', $startDate);
                    $results->execute();
                $results = $results->fetchAll();
        
                return $results;
            }

     /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
    
    public function filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate){
        $user = $this->em->getRepository('AppBundle:User')->find($userId);
        $regionId = $user->getRegionId($idAct);
        $results = $this->findDetailsSurveyByActivityIdPeriodeService($idAct,$startDate,$endDate);
       
        
        $data =[];
        
          foreach ($results as $result) {
           
            
            if ($regionId === -1 || $regionId === 0) {
                if (in_array($result['region_id'],$user->getListOfIdReferenceAreaByActivityId($idAct))) {
                    $data[]= $this->initTableService($result);
                }
            } else {
                if ((intval($result['region_id']) === $regionId) && ($user->getZoneInfluence($idAct) === 5)) {
                    $data[]= $this->initTableService($result);
                }else {
                    if ($user->getZoneInfluence($idAct) === 4) {
                        if (in_array($this->em->getRepository('ApiBundle:Quarter')->find($result['quarter_id'])->getSector()->getId(),$user->getListOfIdReferenceAreaByActivityId($idAct))) {
                            $data[]= $this->initTableService($result);
                        }
                    } else {
                       /*  var_dump(in_array($result['quarter_id'],$user->getListOfIdReferenceAreaByActivityId($idAct))&&(in_array($result['user_id'],$this->getContentIds($idAct,$userId))));
                        die(); */
                        if ((in_array($result['quarter_id'],$user->getListOfIdReferenceAreaByActivityId($idAct)))&&(in_array($result['user_id'],$this->getContentIds($idAct,$userId)))) {
                            $data[]= $this->initTableService($result);
                        }
                    }
                    
                }
            }

        } 
        return $data;
    }

    /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
    public function filterSurveyByUserAndActivityPeriodeSumService($idAct,$userId,$startDate,$endDate){
        
        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];
        $productResult = array();
        $dateResult = array();
        $userResult = array();
        $targetResult = array();
        $dtoResult = array();
        $productName = array();

          foreach ($results as $result) {
            $userResult[$result['user_id']]=0;
            $dtoResult[$result['user_id']] = new UserDto($result['nameBa'],$result['phone']);
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($userResult) as $user_id) {

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                    $targetResult[$result['product_id']]= 0 ;
                    $dateResult[$result['date_submit']]=0;
                }
               
                 $arr = array();
                foreach (array_keys($productResult) as $produit_id) {
                    $arr[$produit_id] = array();
                    foreach (array_keys($dateResult) as $date_value) {
                        $arr[$produit_id][$date_value] = 0;
                    }
                } 

                foreach ($results as $result) {
                        if(intval($result['user_id'])===$user_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }
                        
                foreach (array_keys($productResult) as $product_id) {
                    $data[] = array('product_id' => $product_id,
                                    'quantity'=>$productResult[$product_id],
                                    'quantity_target'=> $targetResult[$product_id],
                                    'user_id'=> $user_id,
                                    'nameBa'=> $dtoResult[$user_id]->getUsername(),
                                    'product_name'=> $productName[$product_id],                                    
                                    'phone'=> $dtoResult[$user_id]->getPhone(),
                                );
                }
            
            }   


        return $data;
    }



     /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
     public function filterSurveyByActivityPeriodeSumService($idAct,$userId,$startDate,$endDate){
        
        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];
        $productResult = array();       
        $productName = array();

          foreach ($results as $result) {
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                }
 
                foreach ($results as $result) {
                     $productResult[$result['product_id']] += $result['quantity'] ;
                }
                    
                        
                foreach (array_keys($productResult) as $product_id) {
                    $data[] = array('product_id' => $product_id,
                                    'quantity'=>$productResult[$product_id],
                                    'product_name'=> $productName[$product_id],                                    
                                    
                                );
                }
            
        return $data;
    }

    /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
    
    public function filterSurveyByUserAndActivityPeriodeSumGroupByDateService($idAct,$userId,$startDate,$endDate){
        
        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];
        $quantityResult = array();
        $productResult = array();
        $dateResult = array();  
        $userResult = array();
        $targetResult = array();

           
           
            foreach (array_keys($userResult) as $user_id) {

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                    $dateResult[$result['date_submit']]=0;
                }

                $arr = array();
                foreach (array_keys($productResult) as $product_id) {
                    $arr[$product_id] = array();
                    foreach (array_keys($dateResult) as $date_value) {
                        $arr[$product_id][$date_value] = 0;
                    }
                } 

                foreach (array_keys($dateResult) as $date_value) {

                    foreach ($results as $result) {
                        $quantityResult[$result['product_id']]=0;
                        $targetResult[$result['product_id']]= 0 ;
                    }
                   
                   
                    
                            foreach ($results as $result) {
                                if((intval($result['user_id'])===$user_id)&&($result['date_submit'] === $date_value) ){

                                    $quantityResult[$result['product_id']] += $result['quantity'] ;

                                if($arr[$result['product_id']][$result['date_submit']]===0){
                                        $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                    $arr[$result['product_id']][$result['date_submit']]=1;
                                }
                                }
                            }
                                
                        foreach (array_keys($productResult) as $product_id) {
                            $data[] = array('product_id' => $product_id,
                                            'quantity'=>$quantityResult[$product_id],
                                            'date_submit'=>$date_value,
                                            'quantity_target'=> $targetResult[$product_id],
                                            'user_id'=> $user_id
                                        );
                        }
                    
                    }   
                }

                

            
        return $data;
    }


/**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
    
     public function diagrammeSurveyPeriodeSumGroupByDateForOneBaService($idBa,$idAct,$startDate,$endDate){
        
        $results = $this->findDetailsSurveyByActivityIdPeriodeServiceOneUser($idBa,$idAct,$startDate,$endDate);
        
        $data =[];
        $quantityResult = array();
        $productResult = array();
        $qte=0;
                $name= '';
                $commit = "";
        /* var_dump($results);
        die(); */
        foreach ($results as $result) {
            $productResult[$result['product_id']]=$result['product_name'];
            $quantityResult[$result['product_id']]=0;
        }

                
        foreach (array_keys($quantityResult) as $product_Value) {     
            foreach ($results as $result) { 
                if((intval($result['product_id']) ===$product_Value)){
                                    
                    $qte += intval($result['quantity']) ;
                    
                    $name = $result['product_name'];
                    $commit = $result['commit'];
                 }
            } 
            
            
            $data[] = array(
                'quantity'=>$qte,
                'product_id'=>$product_Value,
                'name_product'=>$name,
                'commit'=>$commit,
          );
          $qte=0; 
          $name='';
          $commit="";
        }
                  
    return $data;
    }

 /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */ 
    public function DiagrammeDateAndQuantityByService($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        $data =[];
        $quantityResult = array();
        $productResult = array();
        $dateResult = array();
       

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=$result['product_name'];
                }
                
        foreach (array_keys($productResult) as $product_id) {   
            foreach ($results as $result) {
                $dateResult[$result['date_submit']]=0;
              
            }
           ksort($dateResult);
         

            foreach (array_keys($dateResult) as $date_value) {

                                        
                    foreach ($results as $result) {
                        
                        if(($result['date_submit'] === $date_value)&& (intval($result['product_id'])===$product_id)){
                            
                           $dateResult[$result['date_submit']] += intval($result['quantity']) ;
                        }
                    }   
                        }  
                        $data[] = array('product_id' => $product_id,
                                            'quantity'=>array_values($dateResult),
                                            'dates'=>array_keys($dateResult),                                      
                                            'product_name'=>$productResult[$product_id],
                                        );
                        
                    }   
           
        return $data;
    }


/**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */ 
     public function DiagrammeRessourceAndQuantityByService($idAct,$userId,$startDate,$endDate){
        
                $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
                $data =[];
                $nameResources = array();
                $productResult = array();
                $ressourceResult = array();
                $concatNameIdResource = array();
               
        
                        foreach ($results as $result) {
                            $productResult[$result['product_id']]=$result['product_name'];
                        }
                        
                foreach (array_keys($productResult) as $product_id) {   
                    foreach ($results as $result) {
                        $ressourceResult[$result['user_id']]=0;
                        $concatNameIdResource[$result['user_id']]=$result['nameBa'];
                    }
        
                    foreach (array_keys($ressourceResult) as $resource_value) {
 
                            foreach ($results as $result) {
                               
                                if((intval($result['user_id']) ===$resource_value)&& (intval($result['product_id'])===$product_id)){
                                
                                   $ressourceResult[$result['user_id']] += intval($result['quantity']) ;

                                   
                                }
                            }   
                            
                                }  
                                $data[] = array('product_id' => $product_id,
                                                    'quantity'=>array_values($ressourceResult),
                                                    'resource_id'=>array_keys($ressourceResult),                                      
                                                    'product_name'=>$productResult[$product_id],
                                                    'dates'=>array_values($concatNameIdResource),
                                                );
                                
                            }   
                   
                return $data;
            }

            /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */ 
     public function DiagrammeRessourceAndQuantityForAllService($idAct,$userId,$startDate,$endDate){
        
                $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
                $data =[];
                $ressourceResult = array();
                $nameResource = array();
                $qte=0;
                $name= '';
                    
                    foreach ($results as $result) {
                        $ressourceResult[$result['user_id']]=0;
                        $nameResource[$result['user_id']] = $result['nameBa'];
                    }
        
                    foreach (array_keys($ressourceResult) as $resource_value) {
                            foreach ($results as $result) {
                               
                                if((intval($result['user_id']) ===$resource_value)){
                                    
                                   $qte += intval($result['quantity']) ;
                                   
                                   $name = $result['nameBa'];
                                }
                            }   
                            
                            $data[] = array(
                                  'quantity'=>$qte,
                                  'resource_id'=>$resource_value,
                                  'name_ressource'=>$name,
                            );
                       $qte=0; 
                       $name='';        
                            }   
                   
                return $data;
            }


    /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */ 
     public function DiagrammeDateAndQuantityByServiceCopy($idAct,$userId,$startDate,$endDate){
        
                $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
                var_dump($results);
                die();
                $data =[];
                $quantityResult = array();
                $productResult = array();
                $dateResult = array();
                $targetResult = array();
                $productName = array();
        
        
                        foreach ($results as $result) {
                            $productResult[$result['product_id']]=0;
                            $dateResult[$result['date_submit']]=0;
                            $productName[$result['product_id']] = $result['product_name'];                    
                        }
        
                        $arr = array();
                        foreach (array_keys($productResult) as $product_id) {
                            $arr[$product_id] = array();
                            foreach (array_keys($dateResult) as $date_value) {
                                $arr[$product_id][$date_value] = 0;
                            }
                        } 
        
                        foreach (array_keys($dateResult) as $date_value) {
        
                            foreach ($results as $result) {
                                $quantityResult[$result['product_id']]=0;
                                $targetResult[$result['product_id']]= 0 ;
                            }                    
                            foreach ($results as $result) {
                                if(($result['date_submit'] === $date_value) ){
                                    $quantityResult[$result['product_id']] += $result['quantity'] ;
                                if($arr[$result['product_id']][$result['date_submit']]===0){
                                    $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                    $arr[$result['product_id']][$result['date_submit']]=1;
                                    }
                                }
                            }
                                        
                                foreach (array_keys($productResult) as $product_id) {
                                    $data[] = array('product_id' => $product_id,
                                                    'quantity'=>$quantityResult[$product_id],
                                                    'date_submit'=>$date_value,
                                                    'quantity_target'=> $targetResult[$product_id],                                           
                                                    'product_name'=> $productName[$product_id],
                                                );
                                }
                            
                            }   
                    return $data;
            }

    public function initTableService($inArray){
        
        return array('product_id' => $inArray['product_id'],
                        'region_id'=> $inArray['region_id'],
                        'quarter_id'=> $inArray['quarter_id'],
                        'survey_id'=> $inArray['survey_id'] ,
                        'quantity'=> $inArray['quantity'],
                        'quantity_target'=> $inArray['quantity_target'],
                        'user_id'=> $inArray['user_id'],
                        'date_submit'=> $inArray['date_submit'],
                        'date'=> $inArray['date'],
                        'product_name'=> $inArray['product_name'],
                        'nameBa'=> $inArray['nameBa'],
                        'phone'=>$inArray['phone'],
                    );
    }


    public function getFormStructureByActivityId($idAct){
        $products = $this->em->getRepository('ApiBundle:Product')->findByActivityId($idAct);

        $inputs = [];
        foreach ($products as $product) {
           $inputs[] = array('type' => 'number',
                            'name'=> $product->getName(),
                            'placeholder'=> 'quantity',
                            'product_id' => $product->getId(),
                            'value'=> '' ,
                            'quantity'=> '' ,
                            'quantity_in'=> '' ,
                            'required'=> true,
                        );
        }
        $data[] = array('header'=> 'that is survey data with product_survey sub data',
                        'actor_name' => '',
                        'actor_phone'=> '',
                        'user_id'=> '',
                        'commit' => '',
                        'date_submit'=> '' ,
                        'quarter_id'=> '',
                        'pos_id'=> '',
                        'latitude'=> '',
                        'longitude'=> '',
                        'inputsoooo'=> $inputs);
        
        return $data;
    }


    // write product activity logic services ....











    /**
     * Give all survey who had been pushed and using specific target of this area
     *
     * @param [int] $idAct
     * @param [int] $userId
     * @return void
     */
    public function findSurveyByRelativeTargetAndActivityIdAndUserIdProductActivity($idAct,$userId){

        $QUERY = 'SELECT product.activity_id,product.name,SUM(tab1.quantity) AS sumRea,SUM(tab1.quantity_in) sumIn,SUM(tab1.quantity_target) AS sumObj,
        ((SUM(tab1.quantity)/SUM(tab1.quantity_target))*100) AS pourcentage
        FROM survey, product,(SELECT target.product_id AS product_id,ps.id AS product_survey_id,ps.quantity AS quantity,ps.quantityIn AS quantity_in,su.pos_id pos_id, target.quantity AS quantity_target, ps.date_submit AS date_submit,ps.survey_id
                                                    FROM target ,product_survey ps,product p,p_o_s pos,survey su,quarter q,activity_user au,sector s,town t
                                                    WHERE ps.product_id=target.product_id
                                                    AND su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND su.pos_id = pos.id
                                                    AND pos.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.quantityIn IS NOT NULL) AS tab1

            WHERE (survey.user_id = :idUser
            AND product.activity_id = :idAct
            AND survey.id = tab1.survey_id 
            AND product.id = tab1.product_id)
        GROUP BY tab1.product_id ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->bindValue('idUser', $userId);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

    /**
     * Give all summary survey whom had been pushed categorize by product and user          a ne pas use
     *
     * @param [int] $idAct
     * @return void
     */
    public function findSummarySurveyByActivityIdProductActivity($idAct){

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id/* ,target.region_id */,SUM(ps.quantity) AS quantity,SUM(ps.quantityIn) AS quantity_in,SUM(target.quantity) AS quantity_target/* , ps.date_submit AS date_submit,su.date_submit date,ps.survey_id */,su.user_id user_id
                                                    FROM target ,product p,product_survey ps,p_o_s pos,survey su,quarter q,activity_user au,sector s,town t
                                                    WHERE su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND su.pos_id = pos.id
                                                    AND pos.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.quantityIn IS NOT NULL GROUP BY product_id,user_id
        ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

    /**
     * Give all detail survey whom had been pushed 
     *
     * @param [int] $idAct
     * @return void
     */
    public function findDetailsSurveyByActivityIdProductActivity($idAct){

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id,target.region_id ,ps.quantity AS quantity,target.quantity AS quantity_target,ps.quantityIn quantity_in,su.pos_id pos_id,ps.date_submit AS date_submit,su.date_submit date,ps.survey_id,su.user_id user_id
                                                    FROM target ,product p,product_survey ps,survey su,quarter q,p_o_s pos,activity_user au,sector s,town t
                                                    WHERE su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND su.pos_id = pos.id
                                                    AND pos.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.quantityIn IS NOT NULL 
        ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

    /**
     * Give all detail survey whom had been pushed to the office personal
     * related by self influence zone
     *
     * @param [int] $idAct
     * @return void
     */
    public function findDetailsSurveyByActivityIdByEmployeIdProductActivity($idAct){

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id,target.region_id ,ps.quantity AS quantity,target.quantity AS quantity_target,ps.quantityIn quantity_in, ps.date_submit AS date_submit,su.date_submit date,ps.survey_id,su.user_id user_id,su.pos_id pos_id
                                                    FROM target ,product p,product_survey ps,survey su,quarter q,p_o_s pos,activity_user au,sector s,town t
                                                    WHERE su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND su.pos_id = pos.id
                                                    AND pos.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.quantityIn IS NOT NULL 
        ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->execute();
        $results = $results->fetchAll();

        return $results;
    }

    /**
     * Give all survey relative by influence area of this login user for one activity
     *
     * @param [int] $idAct
     * @param [int] $user
     * @return void
     */
    public function filterSurveyByUserAndActivityProduct($idAct,$userId){
        $user = $this->em->getRepository('AppBundle:User')->find($userId);
        $regionId = $user->getRegionId($idAct);
        $results = $this->findDetailsSurveyByActivityIdByEmployeIdProductActivity($idAct);

        $data =[];
        
        foreach ($results as $result) {
            if ($regionId === -1 || $regionId === 0) {
                if (in_array($result['region_id'],$user->getListOfIdReferenceAreaByActivityId($idAct))) {
                    $data[]= $this->initTableProductActivity($result);
                }
            } else {
                if ((intval($result['region_id']) === $regionId) && ($user->getZoneInfluence($idAct) === 5)) {
                    $data[]= $this->initTableProductActivity($result);
                }else {
                    if ($user->getZoneInfluence($idAct) === 4) {
                        if (in_array($this->em->getRepository('ApiBundle:Quarter')->find($result['quarter_id'])->getSector()->getId(),$user->getListOfIdReferenceAreaByActivityId($idAct))) {
                            $data[]= $this->initTableProductActivity($result);
                        }
                    } else {
                        if (in_array($this->em->getRepository('ApiBundle:POS')->find($result['pos_id'])->getQuarter()->getId(),$user->getListOfIdReferenceAreaByActivityId($idAct))&&(in_array($result['user_id'],$this->getContentIds($idAct,$userId)))) {
                            $data[]= $this->initTableProductActivity($result);
                        }
                    }
                    
                }
            }
        }
        
        return $data;
    }


 /**
     * Give all detail survey whom had been pushed 
     *
     * @param [int] $idAct
     * @return void
     */
     public function findDetailsSurveyByActivityIdPeriodeProduct($idAct,$startDate,$endDate){
        
                $QUERY = 'SELECT DISTINCT target.product_id AS product_id,p.name product_name,target.region_id,pos.id pos_id, user.username, user.phone ,ps.quantityIn AS quantity_in ,ps.quantity AS quantity,target.quantity AS quantity_target, ps.date_submit AS date_submit,su.date_submit date,ps.survey_id,su.quarter_id quarter_id,su.user_id user_id
                                                            FROM target ,product p,product_survey ps,survey su,quarter q,activity_user au,sector s,town t,p_o_s pos,user 
                                                            WHERE su.id = ps.survey_id
                                                            AND p.id = ps.product_id
                                                            AND target.product_id =p.id
                                                            AND su.user_id = au.user_id
                                                            AND user.id = su.user_id
                                                            AND su.pos_id = pos.id
                                                            AND pos.quarter_id = q.id
                                                            AND q.sector_id =s.id
                                                            AND s.id = au.sector_id
                                                            AND s.town_id = t.id
                                                            AND target.region_id = t.region_id
                                                            AND au.activity_id = :idAct
                                                            AND target.start_date <= ps.date_submit
                                                            AND target.end_date >= ps.date_submit
                                                            AND ps.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
                                                            AND ps.quantityIn IS NOT NULL 
                ' ; 
                $results = $this->em->getConnection()->prepare($QUERY);
                    $results->bindValue('idAct', $idAct);
                    $results->bindValue('fin', $endDate);
                    $results->bindValue('debut', $startDate);
                    $results->execute();
                $results = $results->fetchAll();
               
                return $results;
            }


/**
     * Give all detail survey whom had been pushed 
     *
     *@param [int] $idBa
     * @param [int] $idAct
     * @return void
     */
    public function findDetailsSurveyByActivityIdPeriodeProductOneUser($idBa,$idAct,$startDate,$endDate){
        
        $QUERY = 'SELECT DISTINCT target.product_id, p.name AS product_name,target.region_id ,ps.quantity AS quantity,
                target.quantity AS quantity_target,ps.id AS productSurveyId, ps.date_submit AS date_submit,ps.commit, ps.quantityIn , user.phone AS phone,
                user.username As nameBa, su.date_submit date,ps.baseline,ps.status,ps.survey_id AS survey,su.quarter_id quarter_id,su.user_id user_id

                                                            FROM target ,product p,product_survey ps,survey su,quarter q,activity_user au,sector s,town t,p_o_s pos,user
                                                            WHERE su.id = ps.survey_id
                                                            AND p.id = ps.product_id
                                                            AND target.product_id =p.id
                                                            AND su.user_id = au.user_id
                                                            AND user.id = su.user_id
                                                            AND user.id = :idBa
                                                            AND su.pos_id = pos.id
                                                            AND pos.quarter_id = q.id
                                                            AND q.sector_id =s.id
                                                            AND s.id = au.sector_id
                                                            AND s.town_id = t.id
                                                            AND target.region_id = t.region_id
                                                            AND au.activity_id = :idAct
                                                            AND target.start_date <= ps.date_submit
                                                            AND target.end_date >= ps.date_submit
                                                            AND ps.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
                                                            AND ps.quantityIn IS NOT NULL
                                                            ORDER BY ps.date_submit 
                ' ; 
                $results = $this->em->getConnection()->prepare($QUERY);
                $results->bindValue('idBa', $idBa);
                    $results->bindValue('idAct', $idAct);
                    $results->bindValue('fin', $endDate);
                    $results->bindValue('debut', $startDate);
                    $results->execute();
                $results = $results->fetchAll();
        
                return $results;
            }

    /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
    
    public function diagrammeSurveyPeriodeSumGroupByDateForOneBaProduct($idBa,$idAct,$startDate,$endDate){
        
        $results = $this->findDetailsSurveyByActivityIdPeriodeProductOneUser($idBa,$idAct,$startDate,$endDate);
        
        $data =[];
        $quantityResult = array();
        $productResult = array();
        $qte=0;
                $name= '';
                $commit = "";
        /* var_dump($results);
        die(); */
        foreach ($results as $result) {
            $productResult[$result['product_id']]=$result['product_name'];
            $quantityResult[$result['product_id']]=0;
        }

                
        foreach (array_keys($quantityResult) as $product_Value) {     
            foreach ($results as $result) { 
                if((intval($result['product_id']) ===$product_Value)){
                                    
                    $qte += intval($result['quantity']) ;
                    
                    $name = $result['product_name'];
                    $commit = $result['commit'];
                 }
            } 
            
            
            $data[] = array(
                'quantity'=>$qte,
                'product_id'=>$product_Value,
                'name_product'=>$name,
                'commit'=>$commit,
          );
          $qte=0; 
          $name='';
        }
                  
    return $data;
    }


    /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
                    
     public function filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate){
        $user = $this->em->getRepository('AppBundle:User')->find($userId);
      
        $regionId = $user->getRegionId($idAct);
        $results = $this->findDetailsSurveyByActivityIdPeriodeProduct($idAct,$startDate,$endDate);

        $data =[];

      
        
          foreach ($results as $result) {
           
            
            if ($regionId === -1 || $regionId === 0) {
                if (in_array(intval($result['region_id']),$user->getListOfIdReferenceAreaByActivityId($idAct))) {
                    $data[]= $this->initTableProductActivity($result);
                }
            } else {
               
                if ((intval($result['region_id']) === $regionId) && ($user->getZoneInfluence($idAct) === 5)) {
                    
                    $data[]= $this->initTableProductActivity($result);
                }else {
                    if ($user->getZoneInfluence($idAct) === 4) {
                        if (in_array($this->em->getRepository('ApiBundle:Quarter')->find($result['quarter_id'])->getSector()->getId(),$user->getListOfIdReferenceAreaByActivityId($idAct))) {
                            $data[]= $this->initTableService($result);
                        }
                    } else {
                      //  var_dump(in_array($result['quarter_id'],$user->getListOfIdReferenceAreaByActivityId($idAct))&&(in_array($result['user_id'],$this->getContentIds($idAct,$userId))));
                        //die();
                        
                        if (in_array($this->em->getRepository('ApiBundle:POS')->find($result['pos_id'])->getQuarter()->getId(),$user->getListOfIdReferenceAreaByActivityId($idAct))&&(in_array($result['user_id'],$this->getContentIds($idAct,$userId)))) {
                            $data[]= $this->initTableProductActivity($result);
                        }
                    }
                    
                }
            }

        } 
        return $data;
    }


    /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */ 
     public function DiagrammeDateAndQuantityByProduct($idAct,$userId,$startDate,$endDate){
        
                $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
                $data =[];
                $quantityResult = array();
                $productResult = array();
                $dateResult = array();
               
        
                        foreach ($results as $result) {
                            $productResult[$result['product_id']]=$result['product_name'];
                        }
                        
                foreach (array_keys($productResult) as $product_id) {   
                    foreach ($results as $result) {
                        $dateResult[$result['date_submit']]=0;
                    }
                    foreach (array_keys($dateResult) as $date_value) {
        
                                                
                            foreach ($results as $result) {
                                
                                if(($result['date_submit'] === $date_value)&& (intval($result['product_id'])===$product_id)){
                                    
                                   $dateResult[$result['date_submit']] += intval($result['quantity']) ;
                                }
                            }   
                                }  
                                $data[] = array('product_id' => $product_id,
                                                    'quantity'=>array_values($dateResult),
                                                    'dates'=>array_keys($dateResult),                                      
                                                    'product_name'=>$productResult[$product_id],
                                                );
                                
                            }   
   
                return $data;
            }

    /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */ 
     public function DiagrammeRessourceAndQuantityByProduct($idAct,$userId,$startDate,$endDate){
        
                $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
                $data =[];
                $nameResources = array();
                $productResult = array();
                $ressourceResult = array();
                $concatNameIdResource = array();
               
        
                        foreach ($results as $result) {
                            $productResult[$result['product_id']]=$result['product_name'];
                        }
                        
                foreach (array_keys($productResult) as $product_id) {   
                    foreach ($results as $result) {
                        $ressourceResult[$result['user_id']]=0;
                        $concatNameIdResource[$result['user_id']]=$result['nameBa'];
                    }
        
                    foreach (array_keys($ressourceResult) as $resource_value) {
                            foreach ($results as $result) {
                               
                                if((intval($result['user_id']) ===$resource_value)&& (intval($result['product_id'])===$product_id)){
                                
                                   $ressourceResult[$result['user_id']] += intval($result['quantity']) ;
        
                                }
                            }   
                            
                                }  
                                $data[] = array('product_id' => $product_id,
                                                    'quantity'=>array_values($ressourceResult),
                                                    'resource_id'=>array_keys($ressourceResult),                                      
                                                    'product_name'=>$productResult[$product_id],
                                                    'dates'=>array_values($concatNameIdResource),
                                                   
                                                );
                                
                            }   
                   
                return $data;
            }


    /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */ 
     public function DiagrammeRessourceAndQuantityForAllProduct($idAct,$userId,$startDate,$endDate){
        
                $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
                $data =[];
                $ressourceResult = array();
                $nameResource = array();
                $qte=0;
                $name= '';
                    
                    foreach ($results as $result) {
                        $ressourceResult[$result['user_id']]=0;
                        $nameResource[$result['user_id']] = $result['nameBa'];
                    }
        
                    foreach (array_keys($ressourceResult) as $resource_value) {
                            foreach ($results as $result) {
                               
                                if((intval($result['user_id']) ===$resource_value)){
                                    
                                   $qte += intval($result['quantity']) ;
                                   
                                   $name = $result['nameBa'];
                                }
                            }   
                            
                            $data[] = array(
                                  'quantity'=>$qte,
                                  'resource_id'=>$resource_value,
                                  'name_ressource'=>$name,
                            );
                       $qte=0; 
                       $name='';        
                            }   
                   
                return $data;
            }
        

    /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
    
     public function filterSurveyByUserAndActivityPeriodeSumProduct($idAct,$userId,$startDate,$endDate){
          
       
        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);

        
        
        $data =[];
        $productResult = array();
        $dateResult = array();
        $userResult = array();
        $quantityInResult = array();
        $targetResult = array();
        $product_name = array();
        $username = array();
        $phone = array();
        

          foreach ($results as $result) {
            $userResult[$result['user_id']]=0;
            $username[$result['user_id']] = $result['nameBa'];
            $phone[$result['user_id']] = $result['phone'];
            $product_name[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($userResult) as $user_id) {

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                    $targetResult[$result['product_id']]= 0 ;
                    $quantityInResult[$result['product_id']] = 0;
                    $dateResult[$result['date_submit']]=0;
                }
               
                 $arr = array();
                foreach (array_keys($productResult) as $produit_id) {
                    $arr[$produit_id] = array();
                    foreach (array_keys($dateResult) as $date_value) {
                        $arr[$produit_id][$date_value] = 0;
                    }
                } 

                foreach ($results as $result) {
                   
                        if(intval($result['user_id'])===$user_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $quantityInResult[$result['product_id']] += $result['quantity_in'];
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }
                        
                foreach (array_keys($productResult) as $product_id) {
                    $data[] = array('product_id' => $product_id,
                                    'quantity'=>$productResult[$product_id],
                                    'quantity_in'=>$quantityInResult[$product_id],
                                    'quantity_target'=> $targetResult[$product_id],
                                    'user_id'=> $user_id,
                                    'product_name' => $product_name[$product_id],
                                    'name_ba' => $username[$user_id],
                                    'phone' => $phone[$user_id] 
                                );
                }
            
            }   
        return $data;
    }


 /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
    
     public function filterSurveyAndActivityPeriodeSumProduct($idAct,$userId,$startDate,$endDate){
                
        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);

        $data =[];
        $productResult = array();
        $product_name = array();

        foreach ($results as $result) {
        $product_name[$result['product_id']] = $result['product_name'];
        } 

        foreach ($results as $result) {
            $productResult[$result['product_id']]=0;
        }

        foreach ($results as $result) {
            $productResult[$result['product_id']] += $result['quantity'] ;
        }
                
        foreach (array_keys($productResult) as $product_id) {
            $data[] = array('product_id' => $product_id,
                            'quantity'=>$productResult[$product_id],
                            'product_name' => $product_name[$product_id],                   
                        );
        }

        return $data;
        }

    public function initTableProductActivity($inArray){
        
        return array('product_id' => $inArray['product_id'],
                        'region_id'=> $inArray['region_id'],
                        'pos_id'=> $inArray['pos_id'],
                        'survey_id'=> $inArray['survey_id'] ,
                        'quantity'=> $inArray['quantity'],
                        'quantity_target'=> $inArray['quantity_target'],
                        'quantity_in'=> $inArray['quantity_in'],
                        'user_id'=> $inArray['user_id'],
                        'date_submit'=> $inArray['date_submit'],
                        'date'=> $inArray['date'],
                        'product_name'=> $inArray['product_name'],
                        'nameBa' => $inArray['username'],
                        'phone' => $inArray['phone'],  
                    );
    }

}