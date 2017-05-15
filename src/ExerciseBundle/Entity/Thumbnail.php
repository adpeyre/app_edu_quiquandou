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
     * 
     *
     * @Assert\NotBlank(message="Aucune image spécifiée")
     * @Assert\File(
     *     maxSize = "500k",
     *     maxSizeMessage = "L'image ne doit pas exéder un poids de {{limit}}",
     *     mimeTypes={ "image/jpeg", "image/png", "image/gif" }),
     *     mimeTypesMessage = "Format invalide. Merci de choisir une image jpeg ou png."
     */
     private $image;

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
        return $this->image;
    }

    public function setImage($image){
        return $this->image = $image;
    }

    public function getImageView(){
        return $this->id;
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

