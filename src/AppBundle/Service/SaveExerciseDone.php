<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class SaveExerciseDone
{

    private $em;
    private $user;

    public function __construct(EntityManager $em){
        $this->user=1;
        $this->em = $em;
    }


    public function add(){

  
        $exerciseDone = new ExerciseDone();
        $exerciseDone->setUser($this->user);
        $this->em->persist($exerciseDone);
        $this->em->flush();

        return $exerciseDone;

        
    }
}