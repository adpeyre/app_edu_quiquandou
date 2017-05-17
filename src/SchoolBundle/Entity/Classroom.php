<?php

namespace SchoolBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class
 *
 * @ORM\Table(name="classroom")
 * @ORM\Entity(repositoryClass="SchoolBundle\Repository\ClassroomRepository")
 */
class Classroom
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     *
     * @Assert\Length(
     *      min = 3,
     *      max = 30,
     *      minMessage = "Le nom de la classe doit comporter au moins {{ limit }} caractères",
     *      maxMessage = "Le nom de la classe comporte trop de caractères"
     * )
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="string", length=9)
     *
     * @Assert\Regex(
     *     pattern     = "/^[0-9]{4}\-[0-9]{4}$/i", 
     *     message = "L'année scolaire définie n'est pas correcte"   
     * )
     */
    private $year;


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
        $this->id = $id;;
        return $this;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Class
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set year
     *
     * @param string $year
     *
     * @return Class
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }



}

