<?php

namespace ExerciseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Feedback
 *
 * @ORM\Table(name="exercise_done_quiquandou")
 * @ORM\Entity(repositoryClass="ExerciseBundle\Repository\ExerciseDoneRepository")
 */
class ExerciseDone
{
    
  
    /**
    * @ORM\Id
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\ExerciseDone",cascade={"persist"})
    * @ORM\JoinColumn(nullable=false,  onDelete="CASCADE")
    */
    private $exerciseDone;


    /**
     * @ORM\ManyToOne(targetEntity="ExerciseBundle\Entity\Exercise", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true,  onDelete="SET NULL")
    */
    private $exercise;

    /**
    *@ORM\Column(name="err_qui",type="smallint")
    */
    private $err_qui;

    /**
    *@ORM\Column(name="err_quand",type="smallint")
    */
    private $err_quand;

    /**
    *@ORM\Column(name="err_ou",type="smallint")
    */
    private $err_ou;

    
    public function getId(){
        return $this->exerciseDone;
    }

    public function setId($id){
        $this->exerciseDone = $id;
        return $this;
    }

    public function setExercise($exercise){
        $this->exercise = $exercise;
        return $this;
    }

    public function getErrQui(){
        return $this->err_qui;
    }

    public function setErrQui($qui){
        $this->err_qui = $qui;
        return $this;
    }

    public function getErrQuand(){
        return $this->err_quand;
    }

    public function setErrQuand($quand){
        $this->err_quand = $quand;
        return $this;
    }

    public function getErrOu(){
        return $this->err_ou;
    }

    public function setErrOu($ou){
        $this->err_ou = $ou;
        return $this;
    }

}

