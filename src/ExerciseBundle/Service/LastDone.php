<?php

namespace ExerciseBundle\Service;

use Doctrine\ORM\EntityManager;

class LastDone
{

    private $em;

    public function __construct(EntityManager $em){
        $this->em = $em;
    }


    public function getList($nb=20,$user=null){
        $results = $this->em->getRepository('ExerciseBundle:ExerciseDone')->getLastDone($nb,$user);  

        $results = array_map(function($ed){

            $ed['score'] = 3 - intval($ed['err_qui']>0)  - intval($ed['err_quand']>0) - intval($ed['err_ou']>0);
           
            return $ed;
        }, $results);      

        return $results;
    }

    
   
}