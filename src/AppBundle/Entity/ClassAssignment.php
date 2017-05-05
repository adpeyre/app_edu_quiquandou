<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ClassAssignment
 *
 * @ORM\Table(name="class_assignment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ClassAssignmentRepository")
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Classroom")
     * @ORM\JoinColumn(nullable=false)
    */
    private $class;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Student")
     * @ORM\JoinColumn(nullable=false)
    */
    private $student;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

