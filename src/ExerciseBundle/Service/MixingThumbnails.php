<?php

namespace ExerciseBundle\Service;

use Doctrine\ORM\EntityManager;

class MixingThumbnails
{

    private $em;

    public function __construct(EntityManager $em){

        $this->em = $em;
    }


    public function getThem($type,$thumbnail){

  
        $thumbnails = $this->em->getRepository('ExerciseBundle:Thumbnail')->getRandom($type,3, array($thumbnail));
       
        $thumbnails[$thumbnail->getId()] = $thumbnail;

        // Mélanger
        //shuffle($thumbnails);
        $keys = array_keys($thumbnails);

        shuffle($keys);

        foreach($keys as $key) {
            $new[$key] = $thumbnails[$key];
        }

        return $new;

       

        //return $thumbnails;

        
    }
}