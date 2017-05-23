<?php

namespace ExerciseBundle\Service;

use Doctrine\ORM\EntityManager;

class GetExercise
{

    private $em;
    private $user;
    private $data;
    private $stats;

    public function __construct(EntityManager $em,$user,$data,$stats){

        $this->em = $em;
        $this->user=$user;
        $this->data = $data;
        $this->stats = $stats;
  
    }


    public function getOne($difficult=null){

        $level = $this->data->getDifficulty();

        // Automatique : on la calcule
        if(empty($difficulty)){
            $level = $this->stats->getLevelAppropriate($this->user);
        }

        $exercise = $this->em->getRepository('ExerciseBundle:ExerciseDone')->getNotDone($this->user,$level);
        
        return $exercise;

        
    }
}