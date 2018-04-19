<?php

namespace ApiBundle\Service;

use Doctrine\ORM\EntityManager;

class LogicService {

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function submitSurvey(){
        $articles = $this->em->getRepository('ApiBundle:Article')->findAll();
        return $articles;
    }

        
}