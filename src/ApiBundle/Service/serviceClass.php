<?php

namespace ApiBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use ApiBundle\Entity\Region;
use ApiBundle\Entity\Activity;
use ApiBundle\Entity\Country;
use ApiBundle\Entity\Entreprise;
use ApiBundle\Entity\POS;
use ApiBundle\Entity\Product;
use ApiBundle\Entity\ProductSurvey;
use ApiBundle\Entity\Quarter;
use ApiBundle\Entity\Role;
use ApiBundle\Entity\Sector;
use ApiBundle\Entity\Survey;
use ApiBundle\Entity\Target;
use ApiBundle\Entity\Town;

class ServiceGlobal 
{
        //pour un jour j
    public function first($region){
   /*      $result = 'SELECT SUM(ps.quantity) AS qteRealiser,
         t.quantity,DATEDIFF(day, 't.start_Date', 't.end_Date') AS diff;
        FROM Product_survey ps, Survey su, Product p, Target t,
        Quarter q, Town t, Sector s, Region r 
        WHERE (p.id = ps.product_id 
            AND p.id = t.product_id
            AND ps.date_submit = t.start_Date
            AND ps.date_submit = t.end_Date
            AND ps.survey_id = su.id
            AND q.id = su.quarter_id
            AND q.sector_id = s.id
            AND s.town_id = to.id
            AND t.region_id = region
            AND to.region_id = region)
        GROUP BY p.id  
        ';
        $em = $this->getDoctrine()->getManager();
        $result = $em->getConnection()->prepare($result);
        $result->bindValue('region', $region);
        $result->execute();
        $result = $result->fetchAll(); */
        return "hello worooooo "+$region;

    }



}