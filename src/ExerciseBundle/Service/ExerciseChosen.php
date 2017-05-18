<?php

namespace ExerciseBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class ExerciseChosen
{

    private $session;

    public function __construct($session){

        $this->session = $session;
        //user
    }


    public function save($data=array()){
        $this->session->set('exercise_data', $data);
    }

    public function get(){
        return $this->session->get('exercise_data');
    }
}