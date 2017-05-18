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
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\ExerciseDone", cascade={"persist"})
    */
    private $exerciseDone;


    /**
     * @ORM\ManyToOne(targetEntity="ExerciseBundle\Entity\Exercise")
     * @ORM\JoinColumn(nullable=true)
    */
    private $exercise;

    /**
    *@ORM\Column(name="qui",type="smallint")
    */
    private $qui;

    /**
    *@ORM\Column(name="quand",type="smallint")
    */
    private $quand;

    /**
    *@ORM\Column(name="ou",type="smallint")
    */
    private $ou;

    
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
        return $this;
    }

    public function setExercise($exercise){
        $this->exercise = $exercise;
        return $this;
    }

    public function getQui(){
        return $this->qui;
    }

    public function setQui($qui){
        $this->qui = $qui;
        return $this;
    }

    public function getQuand(){
        return $this->quand;
    }

    public function setQuand($quand){
        $this->quand = $quand;
        return $this;
    }

    public function getOu(){
        return $this->ou;
    }

    public function setOu($ou){
        $this->ou = $ou;
        return $this;
    }

}

