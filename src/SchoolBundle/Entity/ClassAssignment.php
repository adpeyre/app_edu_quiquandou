<?php

namespace SchoolBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassAssignment
 *
 * @ORM\Table(name="class_assignment")
 * @ORM\Entity(repositoryClass="SchoolBundle\Repository\ClassAssignmentRepository")
 */
class ClassAssignment
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
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\Classroom")
     * @ORM\JoinColumn(nullable=false,  onDelete="CASCADE")
    */
    private $class;

    /**
     * @ORM\ManyToOne(targetEntity="SchoolBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false,  onDelete="CASCADE")
    */
    private $user;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id){
        $this->id=$id;
        return $this;
    }

    public function setClass($class){
        $this->class= $class;
        return $this;
    }

    public function setUser($user){
        $this->user = $user;
        return $this;
    }
}

