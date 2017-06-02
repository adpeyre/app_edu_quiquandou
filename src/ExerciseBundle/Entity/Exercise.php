<?php

namespace ExerciseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Exercise
 *
 * @ORM\Table(name="exercise")
 * @ORM\Entity(repositoryClass="ExerciseBundle\Repository\ExerciseRepository")
 * @ORM\HasLifecycleCallbacks()
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
     * @ORM\Column(type="string",nullable=true)     
     * @Assert\File(maxSize="5M",mimeTypes={ "audio/mpeg" },mimeTypesMessage="Seuls les formats {{type}} sont acceptés pour les enregistrements audio.")
     
     */
    private $sound;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active;


    public function __construct(){
        $this->active = true;
    }


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

    public function getSound(){
        return $this->sound;
    }

    public function setSound($sound){
        $this->sound = $sound;
        return $this;
    }

    public function getSoundSrc(){
        return 'exercise/records/'.$this->getSound();
    }
    
    public function getSoundRootSrc(){
        return __DIR__.'/../../../web/'.$this->getSoundSrc();
    }

    /**
     * @ORM\PostRemove()     
     */
    public function removeSound(){
        if(!empty($this->getSound())){
            unlink($this->getSoundRootSrc());
        }
    }

    public function setActive($active){
        $this->active = $active;
        return $this;
    }

    public function isActive(){
        return $this->active;
    }


    public function getLevelColor(){
        if($this->level == 1)
            return 'success';
        elseif($this->level == 2)
            return 'info';
        elseif($this->level == 3)
            return 'danger';
        else
            return '';
    }


    public static function getLevelsAvailable(){
        return array(
            'Facile'=>1,            
            'Moyen' => 2,            
            'Difficile'=>3            
        );
    }


}

