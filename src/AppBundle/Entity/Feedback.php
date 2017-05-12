<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Feedback
 *
 * @ORM\Table(name="feedback")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FeedbackRepository")
 */
class Feedback
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;


    /**
    *@ORM\Column(name="date",type="datetime")
    */
    private $date;


    /**
   * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\User")
   * @ORM\JoinColumn(nullable=false)
   */
    private $user;

    
    
    public function getId(){
        return $this->id;
    }

    
    public function getDate(){
        return $this->date;       
    }

    public function setDate($date){
        $this->date = $date;;
        return $this;       
    }

    public function getUser(){
        return $this->user;       
    }

    public function setUser($user){
        $this->user = $user;;
        return $this;       
    }

    
}

