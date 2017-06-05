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


    public function getOne(){

        // Si exercice déjà choisi, on recharge le même (rechargement de page)
        if(is_a($this->data->getExercise(), 'ExerciseBundle\Entity\Exercise') ){
            //return $this->data->getExercise();
        }        
       
        $level = $this->data->getDifficulty();

        // Automatique : on la calcule
        if($this->data->getMode() == 0){
            $level = $this->stats->getLevelAppropriate($this->user, $this->data->getDateBegining());
        }
        

        $exercise = $this->em->getRepository('ExerciseBundle:ExerciseDone')->getNotDone($this->user,$level);
        
        return $exercise;

        
    }
}