<?php

namespace ExerciseBundle\Service;

use ExerciseBundle\Entity\Exercise;
use ExerciseBundle\Entity\Thumbnail;

use Doctrine\ORM\EntityManager;

class MixingThumbnails
{

    private $em;
    private $data;


    public function __construct(EntityManager $em, $data){

        $this->em = $em;
        $this->data = $data;
    }


    public function getThem($type,$thumbnail){

        if($type == Thumbnail::QUI && !empty($this->data->getThumbnailsQui()))
            return $this->data->getThumbnailsQui();
        elseif($type == Thumbnail::QUAND && !empty($this->data->getThumbnailsQuand()))
            return $this->data->getThumbnailsQuand();
        elseif($type == Thumbnail::OU && !empty($this->data->getThumbnailsOu()))
            return $this->data->getThumbnailsOu();

        $thumbnails_nb = 4-1;

        if($this->data->getMode() == 1){
            $thumbnails_nb = $this->data->getThumbnailsNb()-1;
        }

  
        $thumbnails = $this->em->getRepository('ExerciseBundle:Thumbnail')->getRandom($type,$thumbnails_nb, array($thumbnail));
       
        $thumbnails[$thumbnail->getId()] = $thumbnail;

        // Mélanger
      
        $keys = array_keys($thumbnails);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $thumbnails[$key];
        }

        return $new;

       

        //return $thumbnails;

        
    }
}