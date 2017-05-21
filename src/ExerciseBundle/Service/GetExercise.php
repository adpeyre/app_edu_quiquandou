<?php

namespace ExerciseBundle\Service;

use Doctrine\ORM\EntityManager;

class GetExercise
{

    private $em;
    private $user;

    public function __construct(EntityManager $em,$user){

        $this->em = $em;
        $this->user=$user;
  
    }


    public function getOne($difficult=null){

        $exercise = $this->em->getRepository('ExerciseBundle:ExerciseDone')->getNotDone($this->user);
        
        return $exercise;

        
    }
}