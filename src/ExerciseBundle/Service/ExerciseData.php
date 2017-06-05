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


    public function getMode(){        
        return $this->get('mode');
    }

    public function setMode($mode){        
        $this->set('mode',$mode);
        return $this;
    }

    public function getDifficulty(){        
        return $this->get('difficulty');
    }

    public function setDifficulty($diff){  
        
        $this->set('difficulty',$diff);
        return $this;
    }

    public function getThumbnailsNb(){        
        return $this->get('thumbnails_nb');
    }

    public function setThumbnailsNb($th){        
        $this->set('thumbnails_nb',$th);
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

    public function getNb(){
        return intval($this->get('nb'));
    }

    public function setNb($nb){
        $this->set('nb',$nb);
        return $this;
    }

    public function getDateBegining(){
        return ($this->get('date_begining'));
    }

    public function setDateBegining($date){
        $this->set('date_begining',$date);
        return $this;
    }

    
    

    private function get($name){
         return $this->session->get('exercise_data['.$name.']');
    }

    private function set($name,$val){
         $this->session->set('exercise_data['.$name.']', $val);
         return $this;
    }

    public function clearCurrentExercise(){
        $this->setExercise(null);
        $this->setThumbnailsQui(null);
        $this->setThumbnailsQuand(null);
        $this->setThumbnailsOu(null);
    }

    public function resetSession(){
        $this->setMode(null);
        $this->setNb(0);
        $this->setDifficulty(null);
        $this->setDateBegining(new \DateTime());

        $this->clearCurrentExercise();

    }

    
}