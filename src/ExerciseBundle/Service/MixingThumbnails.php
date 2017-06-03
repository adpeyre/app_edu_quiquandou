<?php

namespace ExerciseBundle\Service;

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