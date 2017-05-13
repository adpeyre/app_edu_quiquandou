<?php

namespace ExerciceBundle\Service;

use Doctrine\ORM\EntityManager;

class GetExercice
{

    private $em;

    public function __construct(EntityManager $em){

        $this->em = $em;
    }


    public function getOne(){

        //$this->em->getRepository('ExerciceBundle:Feedback')->get


        SELECT * FROM feedback_quiquandou qqo
        JOIN feedback f
        WHERE f.user = ?
        GROUP BY qqo.exercice_id
        HAVING COUNT(*) = MIN( COUNT(*) )
        ORDER BY COUNT(*) ASC
        LIMIT 0,1


        
    }
}