<?php

namespace ExerciseBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class ExerciseData
{


    private $em;
    private $session;

    public function __construct($session){
        //$this->em =$em;
        $this->session = $session;        
    }


    public function getDifficulty(){        
        $this->get('difficulty');
    }

    public function setDifficulty($diff){        
        $this->set('difficulty',$diff);
        return $this;
    }

    public function getExercise(){
        return $this->get('exercise');
    }

    public function setExercise($exercise){
        $this->set('exercise',$exercise);
        return $this;
    }

    public function getThumbnailsQui(){
       return $this->get('ThumbnailsQui');
    }
    
    public function setThumbnailsQui($thumbs){
        $this->set('ThumbnailsQui',$thumbs);
        return $this;
    }

    public function getThumbnailsQuand(){
       return $this->get('ThumbnailsQuand');
    }
    
    public function setThumbnailsQuand($thumbs){
        $this->set('ThumbnailsQuand',$thumbs);
        return $this;
    }

    public function getThumbnailsOu(){
       return $this->get('ThumbnailsOu');
    }
    
    public function setThumbnailsOu($thumbs){
        $this->set('ThumbnailsOu',$thumbs);
        return $this;
    }



    

    private function get($name){
         return $this->session->get('exercise_data['.$name.']');
    }

    private function set($name,$val){
         $this->session->set('exercise_data['.$name.']', $val);
         return $this;
    }
}