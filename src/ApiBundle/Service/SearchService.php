<?php

namespace ApiBundle\Service;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class SearchService 
{
    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function searchActivity($search) {
        $QUERY = 'SELECT *
        FROM activity 
        WHERE (  activity.name LIKE :search OR activity.start_date LIKE :search OR activity.end_date LIKE :search OR activity.id LIKE :search AND activity.status = 1)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    public function searchActivityUser($search) {
        $QUERY = 'SELECT au.*,activity.name activity_name,user.username username,user.phone phone
        FROM activity_user au ,activity,user
        WHERE  au.activity_id = activity.id 
        AND au.user_id = user.id
        AND activity.status = 1
        AND user.enabled = 1
        AND au.status = 1
        AND(activity.name LIKE :search OR user.username LIKE :search OR user.phone LIKE :search )';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();
    return $results;
    }

    public function searchProduct($search) {
        $QUERY = 'SELECT *
        FROM product 
        WHERE (  product.name LIKE :search  AND product.status = 1)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    public function searchCountry($search) {
        $QUERY = 'SELECT *
        FROM country
        WHERE (  country.name LIKE :search  AND region.status = 1)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    public function searchRegion($search) {
        $QUERY = 'SELECT *
        FROM region
        WHERE (  region.name LIKE :search  AND region.status = 1)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    public function searchTown($search) {
        $QUERY = 'SELECT *
        FROM town
        WHERE (  town.name LIKE :search  AND town.status = 1)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    public function searchSector($search) {
        $QUERY = 'SELECT *
        FROM sector
        WHERE (  sector.name LIKE :search  OR sector.code LIKE :search AND sector.status = 1)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    public function searchSurvey($search) {
        $QUERY = 'SELECT *
        FROM survey
        WHERE (  servey.actorName LIKE :search  OR survey.actorPhone LIKE :search AND survey.status = 1)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    public function searchTarget($search) {
        $QUERY = 'SELECT *
        FROM target t
        WHERE (  t.start_date LIKE :search OR t.end_date LIKE :search AND t.status = 1)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }

    public function searchQuarter($search) {
        $QUERY = 'SELECT *
        FROM quarter
        WHERE (  quarter.name LIKE :search AND quarter.status = 1)';
    $results = $this->em->getConnection()->prepare($QUERY);
        $results->bindValue('search','%'.$search.'%');
        $results->execute();
    $results = $results->fetchAll();

    return $results;
    }



}