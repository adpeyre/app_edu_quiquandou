<?php

namespace ExerciseBundle\Service;

use Doctrine\ORM\EntityManager;

class GetExercise
{

    private $em;

    public function __construct(EntityManager $em){

        $this->em = $em;
        //user
    }


    public function getOne($difficult=null){

        $exercise = $this->em->getRepository('ExerciseBundle:ExerciseDone')->getNotDone(1);
        
        return $exercise;

        
    }
}