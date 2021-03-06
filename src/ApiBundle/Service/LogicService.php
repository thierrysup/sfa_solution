<?php

namespace ApiBundle\Service;

use Doctrine\ORM\EntityManager;
use ApiBundle\dto\UserDto;

class LogicService {

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    /**
     * List of subalterns for one manager in all activities
     *
     * @param [int] $id
     * @return void
     */
    public function findSubordinateByManagerId($id){
        $QUERY = 'SELECT DISTINCT u.id id_user,u.username ,u.fistname,u.lastname,u.address ,a.name act_name, a.id act_id
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


     /**
     * find different enterprise who act one user
     *
     * @param [int] $id
     * @return void
     */
    public function findActivitiesByUserId($id){
        $QUERY = 'SELECT DISTINCT en.id id_en,en.name en_name,en.colorStyle en_color,en.logoURL en_logo,ua.user_id user_id,en.adresse adress,a.id id_act, a.name name_act,a.typeActivity type_act,a.start_date start_date,a.end_date end_date
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
        $QUERY = 'SELECT DISTINCT u.id,u.username username,u.firstname,u.lastname,u.address,a.name act_name,a.id activity_id
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
        $QUERY = 'SELECT u.id id,u.username username,u.firstname,u.lastname,u.address,a.id activity_id,(SELECT user.username FROM user WHERE user.enabled = 1 AND um.manager_id = user.id) manager_name,a.name act_name,r.name role_name
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
        $QUERY = 'SELECT DISTINCT(u.id) , u.username ,u.firstname,u.lastname,u.address
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


        /**
     * Give all detail survey whom had been pushed  
     *
     * @param [int] $idAct
     * @return void
     */
    public function pointingResource($idAct,$startDate,$endDate){

        $QUERY = 'SELECT tab2.* , COUNT(*) as present
                    FROM (SELECT DISTINCT survey.user_id , survey.date_submit
                         FROM survey,product_survey ps,product p
                         WHERE  survey.id = ps.survey_id
                         AND ps.product_id = p.id
                         AND p.activity_id = :idAct
                         AND survey.status =1
                         AND survey.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
                         GROUP BY survey.id,survey.date_submit) as tab1 ,(SELECT DISTINCT(u.id) , u.username ,u.phone,u.firstname,u.lastname,u.address
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
                                            AND um.status = 1) AS tab2
                                            
        WHERE tab1.user_id = tab2.id
        GROUP BY tab1.user_id
        ' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
            $results->bindValue('idAct', $idAct);
            $results->bindValue('fin', $endDate);
            $results->bindValue('debut', $startDate);
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

    public function getAct(){

        $QUERY = 'SELECT a.id,a.name nameAct, a.typeActivity ,a.start_date,a.end_date,e.logoURL,e.name nameEntreprise,e.adresse,e.pobox,e.phone
        FROM activity a,entreprise e
        WHERE a.status = 1
        AND e.id = a.entreprise_id
        ';
        $results = $this->em->getConnection()->prepare($QUERY);
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
        $QUERY = 'SELECT DISTINCT(u.id) , u.username , s.description , u.phone,u.firstname,u.lastname,u.address
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
                                                    AND p.activity_id = :idAct
                                                    AND p.activity_id = :idAct
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
                                                    AND p.activity_id = :idAct
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
                                                    AND p.activity_id = :idAct
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
                                                    AND p.activity_id = :idAct
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
     * Deprecated ...
     *
     * @param [int] $idAct
     * @return void
     */
    public function findDetailsSurveyByActivityIdPeriodeServiceOld($idAct,$startDate,$endDate){

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id, p.name AS product_name,target.region_id ,ps.quantity AS quantity,
        target.quantity AS quantity_target, ps.date_submit AS date_submit, user.phone AS phone,
        user.username As nameBa,user.firstname AS firstnameBa,user.lastname  AS lastnameBa,user.address  AS addressBa, su.date_submit date,ps.survey_id,su.quarter_id quarter_id,su.user_id user_id
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
                                                    AND p.activity_id = :idAct
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
     * @param [int] $idAct
     * @return void
     */
    public function findDetailsSurveyByActivityIdPeriodeService($idAct,$startDate,$endDate){

        $QUERY = 'SELECT DISTINCT target.product_id AS product_id, p.name AS product_name,target.region_id ,ps.quantity AS quantity,
        target.quantity AS quantity_target, ps.date_submit AS date_submit, user.phone AS phone,q.id quarter_id,q.name quarter_name,s.id sector_id,s.name sector_name,t.id town_id,t.name town_name,r.id region_id,
        user.username As nameBa,user.firstname AS firstnameBa,user.lastname  AS lastnameBa,user.address  AS addressBa,s.id sector_id,ps.survey_id,su.user_id user_id,r.name region_name,c.id country_id,c.name country_name
                                                    FROM target ,product p,product_survey ps,survey su,quarter q,activity_user au,sector s,town t,user,region r,country c
                                                    WHERE su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND user.id = su.user_id
                                                    AND su.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND t.region_id = r.id
                                                    AND r.country_id = c.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND p.activity_id = :idAct
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
     * @param [int] $idAct
     * @return void
     */
    public function analyseDetailService($idAct,$startDate,$endDate){

        $QUERY = 'SELECT u.username supervisor_name,u.firstname supervisor_firstname,u.lastname supervisor_lastname,u.address supervisor_address,tab1.*
                    FROM user as u ,(SELECT DISTINCT target.product_id AS product_id,um.manager_id AS supervisor_id, p.name AS product_name ,ps.quantity AS quantity,
                                    target.quantity AS quantity_target, ps.date_submit AS date_submit, user.phone AS phone,q.id quarter_id,q.name quarter_name,s.id sector_id,s.name sector_name,t.id town_id,t.name town_name,r.id region_id,
                                       user.username As nameBa,user.firstname AS firstnameBa,user.lastname  AS lastnameBa,user.address  AS addressBa,ps.survey_id,su.user_id user_id,r.name region_name,c.id country_id,c.name country_name
                                                    FROM target ,product p,product_survey ps,survey su,user_manager um,quarter q,activity_user au,sector s,town t,user,region r,country c
                                                    WHERE su.id = ps.survey_id
                                                    AND p.id = ps.product_id
                                                    AND target.product_id =p.id
                                                    AND su.user_id = au.user_id
                                                    AND user.id = su.user_id
                                                    AND su.quarter_id = q.id
                                                    AND q.sector_id =s.id
                                                    AND s.id = au.sector_id
                                                    AND s.town_id = t.id
                                                    AND t.region_id = r.id
                                                    AND r.country_id = c.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND p.activity_id = :idAct
                                                    AND um.activity_id =au.activity_id
                                                    AND um.subordinate_id = su.user_id
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
                                                    AND ps.quantityIn IS NULL) AS tab1 
                                            
        WHERE u.id = tab1.supervisor_id' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
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
        /* var_dump($userId);
        die();  */
        $regionId = $user->getRegionId($idAct);
       
       // $results = $this->findDetailsSurveyByActivityIdPeriodeService($idAct,$startDate,$endDate); old use but deprecated
       $results = $this->analyseDetailService($idAct,$startDate,$endDate);
        
        
        
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
                       /*   var_dump(in_array(intval($result['quarter_id']),$user->getListOfIdReferenceAreaByActivityId($idAct))&&(in_array(intval($result['user_id']),$this->getContentIds($idAct,$userId))));
                        die();  */
                       
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
            $dtoResult[$result['user_id']] = new UserDto($result['nameBa'],$result['phone'],$result['firstnameBa'],$result['lastnameBa'],$result['addressBa']);
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
                                    'nameBa'=> $dtoResult[$user_id]->getFirstname().' '. $dtoResult[$user_id]->getLastname(),
                                    'firstnameBa' => $dtoResult[$user_id]->getFirstname(),
                                    'lastnameBa' => $dtoResult[$user_id]->getLastname(),
                                    'addressBa' => $dtoResult[$user_id]->getAddress(),
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
    public function filterSurveyByUserAndActivityPeriodeSumGroupByDateService($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);

        $data =[];
        $quantityResult = array();
        $productResult = array();
        $dateResult = array();
        $userResult = array();
        $targetResult = array();

        $dtoResult = array();
        $productName = array();

          foreach ($results as $result) {
            $userResult[$result['user_id']]=0;

            $dtoResult[$result['user_id']] = new UserDto($result['nameBa'],$result['phone'],$result['firstnameBa'],$result['lastnameBa'],$result['addressBa']);
            $productName[$result['product_id']] = $result['product_name'];

          }

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
                                            'user_id'=> $user_id,
                                            'nameBa'=> $dtoResult[$user_id]->getFirstname().' '. $dtoResult[$user_id]->getLastname() ,// $dtoResult[$user_id]->getUsername(),
                                            'firstnameBa' => $dtoResult[$user_id]->getFirstname(),
                                            'lastnameBa' => $dtoResult[$user_id]->getLastname(),
                                            'addressBa' => $dtoResult[$user_id]->getAddress(),
                                            'product_name'=> $productName[$product_id],
                                            'phone'=> $dtoResult[$user_id]->getPhone(),
                                        );
                        }
                    }
                }

        return $data;
    }


    /**
     * Diagramms Oriented Services for this Application  Start ++++++++++++++++++
     */

      /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
    public function filterSurveyByUserAndActivityPeriodeSumDiagrammsService($idAct,$userId,$startDate,$endDate){
        
        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
       
        $data =[];
        $productResult = array();
        $productName = array();

          foreach ($results as $result) {
            $productName[$result['product_id']] = $result['product_name'];
            $productResult[$result['product_id']]=0;
          } 

                foreach ($results as $result) {
                            $productResult[$result['product_id']] += intval($result['quantity']) ;
                    }
                        
                    $data = array(
                                    'quantity' => array_values($productResult),
                                    'product_name' => array_values($productName)
                                );

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
    
    public function filterSurveyByUserAndActivityPeriodeSumGroupByDateDiagramsService($idAct,$userId,$startDate,$endDate){
        
        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];
        $quantityResult = array();
        $productResult = array();
        $dateResult = array();  
        $userResult = array();
        $targetResult = array();

        $dtoResult = array();
        $productName = array();
        $productName = array();


          foreach ($results as $result) {
            $productName[$result['product_id']] = $result['product_name'];
            $productResult[$result['product_id']]=0;
            $dateResult[$result['date_submit']]=0;
          } 
       
          ksort($dateResult);

                 $arr = array();
                foreach (array_keys($productResult) as $product_id) {
                    $arr[$product_id] = array();
                    foreach (array_keys($dateResult) as $date_value) {
                        $arr[$product_id][$date_value] = 0;
                    }
                }  

                foreach (array_keys($dateResult) as $date_value) {
                    
                            foreach ($results as $result) {
                                    if($result['date_submit'] === $date_value){
                                    $arr[$result['product_id']][$result['date_submit']]+= intval($result['quantity']);
                                }
                            }
                    
                    }
             $data = array();
            foreach (array_keys($productName) as $product_id) {
                $data[] = array(
                    'product' => $productName[$product_id],
                    'quantity'=> array_values($arr[$product_id]),
                );
            }
            
        return array('products' => array_values($productName), 'date' => array_keys($dateResult), 'data' => $data);
    }



     /**
      *  End Diagramms for this activity ...........
      */



     /**
     * Now we must write a Global report analyse in one town for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getAnalyseResumeDataService($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];
        $dataResource =[];
        $dataDate =[];

        $supervisorArray = array();
        $quantityResult = array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();
        $dtoResult = array();
        $date_list = array();

          foreach ($results as $result) {  
            $supervisorArray[$result['supervisor_id']]= $result['supervisor_firstname'].' '.$result['supervisor_lastname'];
            $productName[$result['product_id']] = $result['product_name'];
            $date_list[$result['date_submit']]=0;
          } 
          ksort($date_list);
           
            foreach (array_keys($supervisorArray) as $supervisor_id) {
                $resourceArray = array();
                foreach ($results as $result) {
                    if (intval($result['supervisor_id']) === $supervisor_id) {
                        $resourceArray[$result['user_id']]=$result['nameBa'];
                        $dtoResult[$result['user_id']] = new UserDto($result['nameBa'],$result['phone'],$result['firstnameBa'],$result['lastnameBa'],$result['addressBa']);
                    }
                }

                $dataResource =[];

            foreach (array_keys($resourceArray) as $user_id) {

                foreach ($results as $result) {
                    $quantityResult[$result['product_id']]=0;
                    $dateResult[$result['date_submit']]=0;
                    ksort($dateResult);
                }
                $dataDate =[];
                $arr = array();
                foreach (array_keys($quantityResult) as $product_id) {
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
                                if((intval($result['user_id'])===$user_id)&&($result['date_submit'] === $date_value)&&(intval($result['supervisor_id']) === $supervisor_id)){

                                    $quantityResult[$result['product_id']] += $result['quantity'] ;

                                if($arr[$result['product_id']][$result['date_submit']]===0){
                                        $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                    $arr[$result['product_id']][$result['date_submit']]=1;
                                }
                                }
                            }

                            $dataDate[] = array(
                                            'quantity'=>array_values($quantityResult),
                                            'date_submit'=>$date_value,
                                            'quantity_target'=>array_values( $targetResult),
                                        ); 
                    
                    }

                    $dataResource[]= array(
                        'nameBa'=> $dtoResult[$user_id]->getFirstname().' '. $dtoResult[$user_id]->getLastname(),
                        'phone'=> $dtoResult[$user_id]->getPhone(),
                        'firstnameBa' => $dtoResult[$user_id]->getFirstname(),
                        'lastnameBa' => $dtoResult[$user_id]->getLastname(),
                        'addressBa' => $dtoResult[$user_id]->getAddress(),
                        'data_date' => $dataDate

                    );

                }

                $data[]= array(
                    'supervisor_name'=> $supervisorArray[$supervisor_id],
                    'supervisor_id'=> $supervisor_id,
                    'product' => array_values($productName),
                    'date_list' =>  array_keys($date_list),
                    'list_resource' => array_values($resourceArray),
                    'data_resource' => $dataResource,
                );
            
            }   

        return $data;

    }

    /**
     * Now we must write a Global report analyse in one town for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getAnalyseResumeDataServiceOld($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];

        $supervisorArray = array();
        $quantityResult = array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();
        $dtoResult = array();

          foreach ($results as $result) {
            $supervisorArray[$result['supervisor_id']]= $result['supervisor_firstname'].' '.$result['supervisor_lastname'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($supervisorArray) as $supervisor_id) {
                $resourceArray = array();
                foreach ($results as $result) {
                    if (intval($result['supervisor_id']) === $supervisor_id) {
                        $resourceArray[$result['user_id']]=$result['nameBa'];
                        $dtoResult[$result['user_id']] = new UserDto($result['nameBa'],$result['phone'],$result['firstnameBa'],$result['lastnameBa'],$result['addressBa']);
                    }
                    
                }

                $dataUser =[];

            foreach (array_keys($resourceArray) as $user_id) {

                foreach ($results as $result) {
                    $quantityResult[$result['product_id']]=0;
                    $dateResult[$result['date_submit']]=0;
                    ksort($dateResult);
                }
               
                $arr = array();
                foreach (array_keys($quantityResult) as $product_id) {
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
                                if((intval($result['user_id'])===$user_id)&&($result['date_submit'] === $date_value)&&(intval($result['supervisor_id']) === $supervisor_id)){

                                    $quantityResult[$result['product_id']] += $result['quantity'] ;

                                if($arr[$result['product_id']][$result['date_submit']]===0){
                                        $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                    $arr[$result['product_id']][$result['date_submit']]=1;
                                }
                                }
                            }

                            $data[] = array(
                                            'supervisor_id'=>$supervisor_id,
                                            'supervisor_name'=> $supervisorArray[$supervisor_id],
                                            'quantity'=>array_values($quantityResult),
                                            'date_submit'=>$date_value,
                                            'quantity_target'=>array_values( $targetResult),
                                            'user_id'=> $user_id,
                                            'nameBa'=> $dtoResult[$user_id]->getFirstname().' '. $dtoResult[$user_id]->getLastname(),
                                            'firstnameBa' => $dtoResult[$user_id]->getFirstname(),
                                            'lastnameBa' => $dtoResult[$user_id]->getLastname(),
                                            'addressBa' => $dtoResult[$user_id]->getAddress(),
                                            'product_name'=> array_values($productName),
                                            'phone'=> $dtoResult[$user_id]->getPhone(),
                                        ); 
                    
                    }

                }
            
            }   

        return $data;

    }




    public function initTableService($inArray){
        
        return array('product_id' => $inArray['product_id'],
                        'region_id'=> $inArray['region_id'],
                        'quarter_id'=> $inArray['quarter_id'],
                        'supervisor_id'=> $inArray['supervisor_id'],
                        'supervisor_name'=> $inArray['supervisor_name'],
                        'supervisor_firstname'=> $inArray['supervisor_firstname'],
                        'supervisor_lastname'=> $inArray['supervisor_lastname'],
                        'supervisor_address'=> $inArray['supervisor_address'],
                        'survey_id'=> $inArray['survey_id'] ,
                        'quantity'=> $inArray['quantity'],
                        'quantity_target'=> $inArray['quantity_target'],
                        'user_id'=> $inArray['user_id'],
                        'date_submit'=> $inArray['date_submit'],
                        'product_name'=> $inArray['product_name'],
                        'nameBa'=> $inArray['nameBa'],
                        'firstnameBa' => $inArray['firstnameBa'],
                        'lastnameBa' => $inArray['lastnameBa'],
                        'addressBa' => $inArray['addressBa'],
                        'phone'=>$inArray['phone'],
                        'region_name'=> $inArray['region_name'],
                        'quarter_name'=> $inArray['quarter_name'],
                        'country_name'=> $inArray['country_name'],
                        'town_name'=> $inArray['town_name'],
                        'sector_id'=> $inArray['sector_id'],
                        'sector_name'=> $inArray['sector_name'],
                        'town_id'=> $inArray['town_id'],
                        'country_id'=> $inArray['country_id'],
                    );
    }


    public function getFormStructureByActivityId($idAct){
        $products = $this->em->getRepository('ApiBundle:Product')->findByActivityId($idAct);

        $inputs = [];
        foreach ($products as $product) {
           $inputs[] = array('type' => 'number',
                            'name'=> $product->getName(),
                            'placeholder'=> '',
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
                        'inputs'=> $inputs);
        
        return $data;
    }


    

    /**
     * Now we must write a Global report analyse in one town for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardTownResumeDataService($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $townArray = array();
        $productResult = array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $townArray[$result['town_id']]= $result['town_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($townArray) as $town_id) {

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
                        if(intval($result['town_id'])===$town_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('town_id' => $town_id,
                            'town_name'=>$townArray[$town_id],
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }   

        return $data;

    }

    /**
     * Now we must write a Global report analyse in one Region for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardRegionResumeDataService($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $regionArray = array();
        $productResult = array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $regionArray[$result['region_id']]= $result['region_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($regionArray) as $region_id) {

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
                        if(intval($result['region_id'])===$region_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('region_id' => $region_id,
                            'region_name'=>$regionArray[$region_id],
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }   

        return $data;

    }

     /**
     * Now we must write a Global report analyse in one Country for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardCountryResumeDataService($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $countryArray = array();
        $productResult = array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $countryArray[$result['country_id']]= $result['country_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($countryArray) as $country_id) {

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
                        if(intval($result['country_id'])===$country_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('country_id' => $country_id,
                            'country_name'=>$countryArray[$country_id],
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }   

        return $data;

    }

    /**
     * Now we must write a Global report analyse in one sector for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardSectorResumeDataService($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $sectorArray = array();
        $productResult = array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $sectorArray[$result['sector_id']]= $result['sector_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($sectorArray) as $sector_id) {

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
                        if(intval($result['sector_id'])===$sector_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('sector_id' => $sector_id,
                            'sector_name'=>$sectorArray[$sector_id],
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }    

        return $data;

    }

    /**
     * Now we must write a Global report analyse in one quarter for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardQuarterResumeDataService($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $quarterArray = array();
        $productResult = array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $quarterArray[$result['quarter_id']]= $result['quarter_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($quarterArray) as $quarter_id) {

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
                        if(intval($result['quarter_id'])===$quarter_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('quarter' => $quarter_id,
                            'quarter_name'=>$quarterArray[$quarter_id],
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }   

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
                                                    AND p.activity_id = :idAct
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
                                                    AND p.activity_id = :idAct
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
                                                    AND p.activity_id = :idAct
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
                                                    AND p.activity_id = :idAct
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
        
                $QUERY = 'SELECT DISTINCT target.product_id AS product_id,p.name product_name,target.region_id,pos.id pos_id,pos.name pos_name,user.phone phone ,
                                    ps.quantityIn AS quantity_in ,ps.quantity AS quantity,target.quantity AS quantity_target, 
                                    ps.date_submit AS date_submit,
                                    ps.survey_id,su.user_id user_id,user.username As nameBa,user.firstname As firstnameBa,user.lastname As lastnameBa,user.address As addressBa,
                                    q.id quarter_id,q.name quarter_name,s.id sector_id,s.name sector_name,t.id town_id,t.name town_name,r.id region_id,
                                    su.date_submit date,r.name region_name,c.id country_id,c.name country_name
                          FROM target ,product p,product_survey ps,survey su,quarter q,activity_user au,sector s,town t,p_o_s pos,user ,region r,country c
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
                                    AND t.region_id = r.id
                                    AND r.country_id = c.id
                                    AND target.region_id = t.region_id
                                    AND p.activity_id = :idAct
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
     * @param [int] $idAct
     * @return void
     */
    public function analyseDetailProduct($idAct,$startDate,$endDate){

        $QUERY = 'SELECT u.username supervisor_name,u.firstname As supervisor_firstname,u.lastname As supervisor_lastname,u.address As supervisor_address,tab1.*
                    FROM user as u ,(SELECT DISTINCT target.product_id AS product_id,um.manager_id AS supervisor_id,ps.quantityIn AS quantity_in, p.name AS product_name ,ps.quantity AS quantity,
                                    target.quantity AS quantity_target, ps.date_submit AS date_submit, user.phone AS phone,q.id quarter_id,q.name quarter_name,s.id sector_id,s.name sector_name,t.id town_id,t.name town_name,r.id region_id,
                                       user.username As nameBa,user.firstname As firstnameBa,user.lastname As lastnameBa,user.address As addressBa,ps.survey_id,su.user_id user_id,r.name region_name,c.id country_id,c.name country_name
                                                    FROM target ,product p,product_survey ps,survey su,p_o_s pos,user_manager um,quarter q,activity_user au,sector s,town t,user,region r,country c
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
                                                    AND t.region_id = r.id
                                                    AND r.country_id = c.id
                                                    AND target.region_id = t.region_id
                                                    AND au.activity_id = :idAct
                                                    AND p.activity_id = :idAct
                                                    AND um.activity_id =au.activity_id
                                                    AND um.subordinate_id = su.user_id
                                                    AND target.start_date <= ps.date_submit
                                                    AND target.end_date >= ps.date_submit
                                                    AND ps.date_submit BETWEEN CAST(:debut AS DATE) AND CAST(:fin AS DATE)
                                                    AND ps.quantityIn IS NOT NULL) AS tab1 
                                            
        WHERE u.id = tab1.supervisor_id' ; 
        $results = $this->em->getConnection()->prepare($QUERY);
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
                    
     public function filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate){
        $user = $this->em->getRepository('AppBundle:User')->find($userId);
        $regionId = $user->getRegionId($idAct);
       // $results = $this->findDetailsSurveyByActivityIdPeriodeProduct($idAct,$startDate,$endDate); // deprecated  ...
        $results = $this->analyseDetailProduct($idAct,$startDate,$endDate);

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
            $username[$result['user_id']] = $result['firstnameBa'].' '.$result['lastnameBa'];
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

                //$arr[1]['2018-04-20']
                //var_dump($arr[1]['2018-04-20']);
                //die();

                foreach ($results as $result) {
                   // var_dump(intval($result['user_id'])===$user_id);
                   // die();
                        if(intval($result['user_id'])===$user_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $quantityInResult[$result['product_id']] += $result['quantity_in'];
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }
                        
              //  }
                // var_dump($productResult);
               // die();
                foreach (array_keys($productResult) as $product_id) {
                    $data[] = array('product_id' => $product_id,
                                    'quantity'=>$productResult[$product_id],
                                    'quantity_in'=>$quantityInResult[$product_id],
                                    'quantity_target'=> $targetResult[$product_id],
                                    'user_id'=> $user_id,
                                    'product_name' => $product_name[$product_id],
                                    'nameBa' => $username[$user_id],
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
    
    public function filterSurveyByUserAndActivityPeriodeSumGroupByDateProduct($idAct,$userId,$startDate,$endDate){
        
        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
        
        $data =[];
        $quantityResult = array();
        $productResult = array();
        $quantityInResult = array();
        $dateResult = array();  
        $userResult = array();
        $targetResult = array();

        $dtoResult = array();
        $productName = array();

          foreach ($results as $result) {
            $userResult[$result['user_id']]=0;
            $dtoResult[$result['user_id']] = new UserDto($result['nameBa'],$result['phone'],$result['firstnameBa'],$result['lastnameBa'],$result['addressBa']);
            //$quantityInResult[$result['product_id']] = 0;
            $productName[$result['product_id']] = $result['product_name'];
          } 

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
                        $quantityInResult[$result['product_id']] = 0;
                    }
                    
                            foreach ($results as $result) {
                                if((intval($result['user_id'])===$user_id)&&($result['date_submit'] === $date_value) ){

                                    $quantityResult[$result['product_id']] += $result['quantity'] ;

                                if($arr[$result['product_id']][$result['date_submit']]===0){
                                        $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                        $quantityInResult[$result['product_id']] += $result['quantity_in'];
                                        $arr[$result['product_id']][$result['date_submit']]=1;
                                }
                                }
                            }
                                
                        foreach (array_keys($productResult) as $product_id) {
                            $data[] = array('product_id' => $product_id,
                                            'quantity'=>$quantityResult[$product_id],
                                            'date_submit'=>$date_value,
                                            'quantity_target'=> $targetResult[$product_id],
                                            'quantity_in'=>$quantityInResult[$product_id],
                                            'user_id'=> $user_id,
                                            'firstnameBa'=> $dtoResult[$user_id]->getFirstname(),
                                            'lastnameBa'=> $dtoResult[$user_id]->getLastname(),
                                            'nameBa'=> $dtoResult[$user_id]->getFirstname().' '. $dtoResult[$user_id]->getLastname(),
                                            'addressBa'=> $dtoResult[$user_id]->getAddress(),
                                            'product_name'=> $productName[$product_id],                                    
                                            'phone'=> $dtoResult[$user_id]->getPhone(),
                                        );
                        }
                    
                    }   
                }

                

            
        return $data;
    }



      /**
     * Now we must write a Global report analyse in one town for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getAnalyseResumeDataProduct($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeService($idAct,$userId,$startDate,$endDate);
        
        $data =[];
        $dataResource =[];
        $dataDate =[];

        $supervisorArray = array();
        $quantityResult = array();
        $dateResult = array();
        $quantityInResult = array();
        $targetResult = array();
        $productName = array();
        $dtoResult = array();
        $date_list = array();

          foreach ($results as $result) {
            $supervisorArray[$result['supervisor_id']]= $result['supervisor_firstname'].' '.$result['supervisor_lastname'];
            $productName[$result['product_id']] = $result['product_name'];
            $date_list[$result['date_submit']]=0;
          } 
          ksort($date_list);
           
            foreach (array_keys($supervisorArray) as $supervisor_id) {
                $resourceArray = array();
                foreach ($results as $result) {
                    if (intval($result['supervisor_id']) === $supervisor_id) {
                        $resourceArray[$result['user_id']]=$result['nameBa'];
                        $dtoResult[$result['user_id']] = new UserDto($result['nameBa'],$result['phone'],$result['firstnameBa'],$result['lastnameBa'],$result['addressBa']);
                    }
                }

                $dataResource =[];

            foreach (array_keys($resourceArray) as $user_id) {

                foreach ($results as $result) {
                    $quantityResult[$result['product_id']]=0;
                    $dateResult[$result['date_submit']]=0;
                    ksort($dateResult);
                }
                $dataDate =[];
                $arr = array();
                foreach (array_keys($quantityResult) as $product_id) {
                    $arr[$product_id] = array();
                    foreach (array_keys($dateResult) as $date_value) {
                        $arr[$product_id][$date_value] = 0;
                    }
                } 

                foreach (array_keys($dateResult) as $date_value) {

                    foreach ($results as $result) {
                        $quantityResult[$result['product_id']]=0;
                        $targetResult[$result['product_id']]= 0 ;
                        $quantityInResult[$result['product_id']] = 0;
                    }
                    
                            foreach ($results as $result) {
                                if((intval($result['user_id'])===$user_id)&&($result['date_submit'] === $date_value)&&(intval($result['supervisor_id']) === $supervisor_id)){

                                    $quantityResult[$result['product_id']] += $result['quantity'] ;

                                if($arr[$result['product_id']][$result['date_submit']]===0){
                                        $targetResult[$result['product_id']] += intval($result['quantity_target']) ;
                                        $quantityInResult[$result['product_id']] += intval($result['quantity_in']);
                                    $arr[$result['product_id']][$result['date_submit']]=1;
                                }
                                }
                            }

                            $dataDate[] = array(
                                            'quantity'=>array_values($quantityResult),
                                            'date_submit'=>$date_value,
                                            'quantity_target'=>array_values( $targetResult),
                                            'quantity_in'=>array_values($quantityInResult),
                                        ); 
                    
                    }

                    $dataResource[]= array(
                        'nameBa'=> $dtoResult[$user_id]->getFirstname().' '.$dtoResult[$user_id]->getLastname(),
                        'firstnameBa'=> $dtoResult[$user_id]->getFirstname(),
                        'lastnameBa'=> $dtoResult[$user_id]->getLastname(),
                        'addressBa'=> $dtoResult[$user_id]->getAddress(),
                        'phone'=> $dtoResult[$user_id]->getPhone(),
                        'data_date' => $dataDate

                    );

                }

                $data[]= array(
                    'supervisor_name'=> $supervisorArray[$supervisor_id],
                    'supervisor_id'=> $supervisor_id,
                    'product' => array_values($productName),
                    'list_resource' => array_values($resourceArray),
                    'date_list' =>  array_keys($date_list),
                    'data_resource' => $dataResource
                );
            
            }   

        return $data;
    }


    /**
     * Diagramms Oriented Product for this Application  Start ++++++++++++++++++
     */

      /**
     * Give all survey relative by influence area of this login user for one activity on a period
     *
     * @param [int] $idAct
     * @param [int] $user
     * @param [date] $startDate
     * @param [date] $endDate
     * @return void
     */
    public function filterSurveyByUserAndActivityPeriodeSumDiagrammsProduct($idAct,$userId,$startDate,$endDate){
        
        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
       
        $data =[];
        $productResult = array();
        $productName = array();

          foreach ($results as $result) {
            $productName[$result['product_id']] = $result['product_name'];
            $productResult[$result['product_id']]=0;
          } 

                foreach ($results as $result) {
                            $productResult[$result['product_id']] += intval($result['quantity']) ;
                    }
                        
                //foreach (array_keys($productResult) as $product_id) {
                    $data = array(
                                    'quantity' => array_values($productResult),
                                    'product_name' => array_values($productName)
                                );
               // }

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
    
    public function filterSurveyByUserAndActivityPeriodeSumGroupByDateDiagramsProduct($idAct,$userId,$startDate,$endDate){
        
        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
        
        $data =[];
        $quantityResult = array();
        $productResult = array();
        $dateResult = array();  
        $userResult = array();
        $targetResult = array();

        $dtoResult = array();
        $productName = array();

          foreach ($results as $result) {
            $productName[$result['product_id']] = $result['product_name'];
            $productResult[$result['product_id']]=0;
            $dateResult[$result['date_submit']]=0;
          } 
          ksort($dateResult);

                 $arr = array();
                foreach (array_keys($productResult) as $product_id) {
                    $arr[$product_id] = array();
                    foreach (array_keys($dateResult) as $date_value) {
                        $arr[$product_id][$date_value] = 0;
                    }
                }  

                foreach (array_keys($dateResult) as $date_value) {
                    
                            foreach ($results as $result) {
                                    if($result['date_submit'] === $date_value){
                                    $arr[$result['product_id']][$result['date_submit']]+= intval($result['quantity']);
                                }
                            }
                    
                    }
             $data = array();
            foreach (array_keys($productName) as $product_id) {
                $data[] = array(
                    'product' => $productName[$product_id],
                    'quantity'=> array_values($arr[$product_id]),
                );
            }
            
        return array('products' => array_values($productName), 'date' => array_keys($dateResult), 'data' => $data);
    }



     /**
      *  End Diagramms for this activity ...........
      */



    public function initTableProductActivity($inArray){
        
        return array('product_id' => $inArray['product_id'],
                        'region_id'=> $inArray['region_id'],
                        'pos_id'=> $inArray['pos_id'],
                        'pos_name'=> $inArray['pos_name'],
                        'supervisor_id'=> $inArray['supervisor_id'],
                        'supervisor_name'=> $inArray['supervisor_name'],
                        'supervisor_firstname'=> $inArray['supervisor_firstname'],
                        'supervisor_lastname'=> $inArray['supervisor_lastname'],
                        'supervisor_address'=> $inArray['supervisor_address'],
                        'survey_id'=> $inArray['survey_id'] ,
                        'quantity'=> $inArray['quantity'],
                        'quantity_target'=> $inArray['quantity_target'],
                        'quantity_in'=> $inArray['quantity_in'],
                        'user_id'=> $inArray['user_id'],
                        'date_submit'=> $inArray['date_submit'],
                        'product_name'=> $inArray['product_name'],
                        'nameBa' => $inArray['nameBa'],
                        'firstnameBa' => $inArray['firstnameBa'],
                        'lastnameBa' => $inArray['lastnameBa'],
                        'addressBa' => $inArray['addressBa'],
                        'phone' => $inArray['phone'],
                        'region_name'=> $inArray['region_name'],
                        'quarter_name'=> $inArray['quarter_name'],
                        'country_name'=> $inArray['country_name'],
                        'town_name'=> $inArray['town_name'],
                        'sector_id'=> $inArray['sector_id'],
                        'sector_name'=> $inArray['sector_name'],
                        'town_id'=> $inArray['town_id'],
                        'country_id'=> $inArray['country_id'],
                    );
    }


     /**
     * Now we must write a Global report analyse in one town for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardTownResumeDataProduct($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $townArray = array();
        $productResult = array();
        $quantityInResult = array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $townArray[$result['town_id']]= $result['town_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($townArray) as $town_id) {

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                    $targetResult[$result['product_id']]= 0 ;
                    $quantityInResult[$result['product_id']]= 0 ;
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
                        if(intval($result['town_id'])===$town_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                $quantityInResult[$result['product_id']] += $result['quantity_in'];
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('town_id' => $town_id,
                            'town_name'=>$townArray[$town_id],
                            'quantity_in' =>array_values($quantityInResult),
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }   

        return $data;

    }

    /**
     * Now we must write a Global report analyse in one Region for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardRegionResumeDataProduct($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodepProduct($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $regionArray = array();
        $productResult = array();
        $quantityInResult = array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $regionArray[$result['region_id']]= $result['region_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($regionArray) as $region_id) {

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                    $targetResult[$result['product_id']]= 0 ;
                    $quantityInResult[$result['product_id']]= 0 ;
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
                        if(intval($result['region_id'])===$region_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                $quantityInResult[$result['product_id']] += $result['quantity_in'];
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('region_id' => $region_id,
                            'region_name'=>$regionArray[$region_id],
                            'quantity_in' =>array_values($quantityInResult),
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }   

        return $data;

    }

     /**
     * Now we must write a Global report analyse in one Country for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardCountryResumeDataProduct($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $countryArray = array();
        $productResult = array();
        $dateResult = array();
        $quantityInResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $countryArray[$result['country_id']]= $result['country_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($countryArray) as $country_id) {

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                    $targetResult[$result['product_id']]= 0 ;
                    $quantityInResult[$result['product_id']]= 0 ;
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
                        if(intval($result['country_id'])===$country_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                $quantityInResult[$result['product_id']] += $result['quantity_in'];
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('country_id' => $country_id,
                            'country_name'=>$countryArray[$country_id],
                            'quantity_in' =>array_values($quantityInResult),
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }   

        return $data;

    }

    /**
     * Now we must write a Global report analyse in one sector for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardSectorResumeDataProduct($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $sectorArray = array();
        $productResult = array();
        $dateResult = array();
        $quantityInResult= array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $sectorArray[$result['sector_id']]= $result['sector_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($sectorArray) as $sector_id) {

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                    $targetResult[$result['product_id']]= 0 ;
                    $quantityInResult[$result['product_id']]= 0 ;
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
                        if(intval($result['sector_id'])===$sector_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                $quantityInResult[$result['product_id']] += $result['quantity_in'];
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('sector_id' => $sector_id,
                            'sector_name'=>$sectorArray[$sector_id],
                            'quantity_in' =>array_values($quantityInResult),
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }   

        return $data;

    }

    /**
     * Now we must write a Global report analyse in one quarter for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardQuarterResumeDataProduct($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $quarterArray = array();
        $productResult = array();
        $quantityInResult=array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $quarterArray[$result['quarter_id']]= $result['quarter_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($quarterArray) as $quarter_id) {

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                    $targetResult[$result['product_id']]= 0 ;
                    $quantityInResult[$result['product_id']]= 0 ;
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
                        if(intval($result['quarter_id'])===$quarter_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                $quantityInResult[$result['product_id']] += $result['quantity_in'];
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('quarter' => $quarter_id,
                            'quarter_name'=>$quarterArray[$quarter_id],
                            'quantity_in' =>array_values($quantityInResult),
                            'product' => array_values($productName),
                            'quantity' => array_values($productResult),
                            'quantity_target'=>array_values($targetResult),
                        );
            
            }   

        return $data;

    }

    /**
     * Now we must write a Global report analyse in one pos for those user
     * this report help some to take some strategic decision about those 
     * activity and appreciate state situation of trade.
     * 
     */
    public function getDashBoardPosResumeDataProduct($idAct,$userId,$startDate,$endDate){

        $results = $this->filterSurveyByUserAndActivityPeriodeProduct($idAct,$userId,$startDate,$endDate);
        
        $data =[];


        $posArray = array();
        $productResult = array();
        $quantityInResult=array();
        $dateResult = array();
        $targetResult = array();
        $productName = array();

          foreach ($results as $result) {
            $posArray[$result['pos_id']]= $result['pos_name'];
            $productName[$result['product_id']] = $result['product_name'];
          } 
           
            foreach (array_keys($posArray) as $pos_id) {

                foreach ($results as $result) {
                    $productResult[$result['product_id']]=0;
                    $targetResult[$result['product_id']]= 0 ;
                    $quantityInResult[$result['product_id']]= 0 ;
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
                        if(intval($result['pos_id'])===$pos_id){

                            $productResult[$result['product_id']] += $result['quantity'] ;

                           if($arr[$result['product_id']][$result['date_submit']]===0){
                                $targetResult[$result['product_id']] += $result['quantity_target'] ;
                                $quantityInResult[$result['product_id']] += $result['quantity_in'];
                               $arr[$result['product_id']][$result['date_submit']]=1;
                           }
                        }
                    }

                $data[] = array('pos' => $pos_id,
                            'pos_name'=>$posArray[$pos_id],
                            'quantity_in' =>$quantityInResult,
                            'product' => $productName,
                            'quantity' => $productResult,
                            'quantity_target'=>$targetResult,
                        );
            
            }   

        return $data;

    }


}