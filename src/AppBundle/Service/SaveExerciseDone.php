<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;


class SaveExerciseDone
{

    private $em;
    private $user;

    public function __construct(EntityManager $em, $user){
        $this->user=$user;
        $this->em = $em;
    }


    public function add(){

  
        $exerciseDone = new \AppBundle\Entity\ExerciseDone();
        $exerciseDone->setUser($this->user);
        $this->em->persist($exerciseDone);
        $this->em->flush();

        return $exerciseDone;

        
    }
}