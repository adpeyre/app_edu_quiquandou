<?php

namespace ExerciseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Thumbnail
 *
 * @ORM\Table(name="thumbnail")
 * @ORM\Entity(repositoryClass="ExerciseBundle\Repository\ThumbnailRepository")
 */
class Thumbnail
{

    const QUI = 1;
    const QUAND = 2;
    CONST OU = 3;

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
     * @ORM\Column(name="name", type="string", length=100)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="type", type="smallint")
     */
    private $type;


   

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Thumbnail
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
     * Set type
     *
     * @param integer $type
     *
     * @return Thumbnail
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType(){
        return $this->type;
    }


    public function getImage(){
        return empty($this->image) ? "" : $this->image;
    }

    public function setImage($image){
        return $this->image = $image;
    }

    public function getImageView(){
        return 'exercise/thumbnails/'.$this->id;        
    }

    public function getImageRoot(){
        return __DIR__.'/../../../web/'.getImageView(); 
    }

    public  function getTypeName(){
        switch ($this->type){
            case 1 : "qui";break;
            case 2 : "quand"; break;
            case 3 : "ou"; break;
        }
    }


    public function __toString(){
        return $this->getName();
    }

    public static function getTypesList(){
        return array(
            "qui" => 1,
            "quand" =>2 ,
            "où" => 3
        );
    }


}

