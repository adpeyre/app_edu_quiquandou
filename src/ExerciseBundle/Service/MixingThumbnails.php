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
        
        array_push($thumbnails,$thumbnail);

        shuffle($thumbnails);

        return $thumbnails;

        
    }
}