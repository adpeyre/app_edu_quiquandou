<?php

namespace ExerciceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Exercice
 *
 * @ORM\Table(name="exercice")
 * @ORM\Entity(repositoryClass="ExerciceBundle\Repository\ExerciceRepository")
 */
class Exercice
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
     * @ORM\ManyToOne(targetEntity="ExerciceBundle\Entity\Thumbnail")
     * @ORM\JoinColumn(nullable=true)
    */
    private $qui;

    /**
     * @ORM\ManyToOne(targetEntity="ExerciceBundle\Entity\Thumbnail")
     * @ORM\JoinColumn(nullable=true)
    */
    private $quand;

    /**
     * @ORM\ManyToOne(targetEntity="ExerciceBundle\Entity\Thumbnail")
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
     * @return Exercice
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
     * @return Exercice
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

    /**
     * Set qui
     *
     * @param integer $qui
     *
     * @return Exercice
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
     * @return Exercice
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
     * @return Exercice
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
            '1 - Très facile'=>1,
            '2 - Très facile' => 2,
            '3 - Facile' => 3,
            '4 - Facile' => 4,
            '5 - Moyen' => 5,
            '6 - Moyen' => 6,
            '7 - Difficile' => 7,
            '8 - Difficile' => 8,
            '9 - Très difficile'=>9,
            '10 - Très difficile'=>10            
        );
    }
}

