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

    public function getAttempts($type=''){
        return intval($this->get('attempts_'.$type));
    }

    public function setAttempts($type='',$attempts){
        $this->set('attempts_'.$type,$attempts);
        return $this;
    }

    public function getWrongThumb(){
        $array = $this->get('wrong_thumb');
        return empty($array) ? array() : $array;
    }

    public function setWrongThumb($wrong){
        $this->set('wrong_thumb',$wrong);
        return $this;
    }

    public function getThumbLastSelected(){
        return $this->get('thumb_last_selected');
    }

    public function setThumbLastSelected($thumbs){
        $this->set('thumb_last_selected',$thumbs);
        return $this;
    }

    public function getDateBegining(){
        return ($this->get('date_begining'));
    }

    public function setDateBegining($date){
        $this->set('date_begining',$date);
        return $this;
    }

    public function exerciseIsEnded(){
        return $this->get('exercise_ended');
    }

    public function setExerciseEnded($val=true){
        $this->set('exercise_ended',$val);
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
        $this->setExerciseEnded(false);
        $this->setWrongThumb(array());
        $this->setAttempts('',0);
        $this->setAttempts('qui',0);
        $this->setAttempts('quand',0);
        $this->setAttempts('ou',0);
        $this->setThumbLastSelected(array());
    }

    public function resetSession(){
        $this->setMode(null);
        $this->setNb(0);
        $this->setDifficulty(null);
        $this->setDateBegining(new \DateTime());

        $this->clearCurrentExercise();

    }

    
}