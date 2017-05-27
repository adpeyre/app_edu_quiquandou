<?php

namespace ExerciseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Exercise
 *
 * @ORM\Table(name="exercise")
 * @ORM\Entity(repositoryClass="ExerciseBundle\Repository\ExerciseRepository")
 */
class Exercise
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
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;



    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     */
    private $text;

    /**
     * @var int
     *
     * @ORM\Column(name="level", type="smallint")
     *     
     * @Assert\Choice(
     *     callback = "getLevelsAvailable",
     *     message = "Niveau selectionné invalide"
     * )   
     */
    private $level;

    /**
     * @ORM\ManyToOne(targetEntity="ExerciseBundle\Entity\Thumbnail")
     * @ORM\JoinColumn(nullable=true)
    */
    private $qui;

    /**
     * @ORM\ManyToOne(targetEntity="ExerciseBundle\Entity\Thumbnail")
     * @ORM\JoinColumn(nullable=true)
    */
    private $quand;

    /**
     * @ORM\ManyToOne(targetEntity="ExerciseBundle\Entity\Thumbnail")
     * @ORM\JoinColumn(nullable=true)
    */
    private $ou;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    public function getTitle(){
        return $this->title;
    }

    public function setTitle($title){
        $this->title = $title;
        return $this;
    }

    /**
     * Set text
     *
     * @param string $text
     *
     * @return Exercise
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set level
     *
     * @param integer $level
     *
     * @return Exercise
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    public function getLevelName(){
        $l =  array_flip(self::getLevelsAvailable());
        return (array_key_exists($this->level,$l)) ? $l[$this->level] : "Inconnu";
    }

    /**
     * Set qui
     *
     * @param integer $qui
     *
     * @return Exercise
     */
    public function setQui($qui)
    {
        $this->qui = $qui;

        return $this;
    }

    /**
     * Get qui
     *
     * @return int
     */
    public function getQui()
    {
        return $this->qui;
    }

    /**
     * Set quand
     *
     * @param integer $quand
     *
     * @return Exercise
     */
    public function setQuand($quand)
    {
        $this->quand = $quand;

        return $this;
    }

    /**
     * Get quand
     *
     * @return int
     */
    public function getQuand()
    {
        return $this->quand;
    }

    /**
     * Set ou
     *
     * @param integer $ou
     *
     * @return Exercise
     */
    public function setOu($ou)
    {
        $this->ou = $ou;

        return $this;
    }

    /**
     * Get ou
     *
     * @return int
     */
    public function getOu()
    {
        return $this->ou;
    }


    public static function getLevelsAvailable(){
        return array(
            'Facile'=>1,            
            'Moyen' => 2,            
            'Difficile'=>3            
        );
    }
}

