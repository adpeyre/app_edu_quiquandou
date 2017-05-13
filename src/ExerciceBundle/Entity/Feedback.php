<?php

namespace ExerciceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Feedback
 *
 * @ORM\Table(name="feedback_quiquandou")
 * @ORM\Entity(repositoryClass="ExerciceBundle\Repository\FeedbackRepository")
 */
class Feedback
{
    /**
    * @ORM\Id
    * @ORM\OneToOne(targetEntity="AppBundle\Entity\Feedback", cascade={"persist"})
    */
    private $feedback;


    /**
     * @ORM\ManyToOne(targetEntity="ExerciceBundle\Entity\Exercice")
     * @ORM\JoinColumn(nullable=true)
    */
    private $exercice;

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

