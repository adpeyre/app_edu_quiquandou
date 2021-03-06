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
     */
    private $sound;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */

    private $active;

    /**         
     * @Assert\File(maxSize="5M",mimeTypes={ "audio/mpeg" },mimeTypesMessage="Seuls les formats {{type}} sont acceptés pour les enregistrements audio.")     
     */
    private $file;


    private $tmp_sound;
    private $tmp_sound_action = '';

    public function __construct(){
        $this->active = true;
        $this->tmp_sound = null;
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

    public function setSound($sound=null){
        
        if(is_null($sound)){
            $this->removeSound(); 
        }

        $this->sound = $sound;
        
        return $this;
    }

    public function isSoundAuto(){
        return preg_match('/^auto/',$this->sound);
    }

    public function getSoundDir(){
        $return =  'exercise/records';        
        return $return;
    }
    
    public function getSoundRootDir(){
        return __DIR__.'/../../../web/'.$this->getSoundDir();
    }

    public function getSoundView(){
        return $this->getSoundDir().'/'.$this->sound;
    }

    public function getFile(){
        return $this->file;
    }

    public function setFile($file=null){
        $this->file = $file;

        if(!empty($this->sound)){
            $this->tmp_sound = $this->sound;            
        }
       
        if (null !== $this->file) {   
                   
            $this->sound = uniqid().'_'.$this->getFile()->getClientOriginalName();
        }
         
       

        return $this;

    }


    

    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function upload()
    {

        
        if (null === $this->getFile()) {
            return;
        }
       
        // if there is an error when moving the file, an exception will
        // be automatically thrown by move(). This will properly prevent
        // the entity from being persisted to the database on error
        $this->getFile()->move($this->getSoundRootDir(), $this->sound);

        // check if we have an old image
        if (isset($this->tmp_sound)) {
            // delete the old image
            $this->removeSound($this->tmp_sound);
            // clear the temp image path
            $this->tmp_sound = null;
        }
        $this->file = null;
    }

    

    /**
     * @ORM\PostRemove()     
     */
    public function removeSound($file=null){
        if(!is_null($file) && !is_object($file))
            $fileToDelete = $file;
        else
            $fileToDelete = $this->getSound();

        
        
        if(!empty($fileToDelete) && file_exists($this->getSoundRootDir().'/'.$fileToDelete)){
            
            unlink($this->getSoundRootDir().'/'.$fileToDelete);
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


    public function __toString() {
        return $this->title;
    }


    public static function getLevelsAvailable(){
        return array(
            'Facile'=>1,            
            'Moyen' => 2,            
            'Difficile'=>3            
        );
    }


}

