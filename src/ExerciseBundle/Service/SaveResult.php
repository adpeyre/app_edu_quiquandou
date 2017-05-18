<?php

namespace ExerciseBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class SaveResult
{

    private $em;
    private $saveDone;
    private $session;

    public function __construct($em,$saveDone, $session){

        $this->em = $em;
        $this->saveDone = $saveDone;
        $this->session = $session;
        //user
    }


    public function save($exercise, $err_qui,$err_quand,$err_ou){
        //$this->session->set('exercise_data', $data);

        $saveDoneGeneral = $saveDone->add();
        $id = $saveDoneGeneral->getId();

        $exerciseDone = new ExerciseDone();
        $exerciseDone
            ->setId($id)
            ->setExercise($exercise)
            ->setQui($err_qui)
            ->setQuand($err_quand)
            ->setOu($err_ou);

        $this->em->persist($exerciseDone);
        $this->em->flush();
    }

    
}