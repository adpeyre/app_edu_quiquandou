<?php

namespace ExerciseBundle\Service;

use Symfony\Component\HttpFoundation\Session\Session;

class SaveResult
{

    private $em;
    private $saveDone;
    private $session;

    public function __construct($em,$saveDone, $session){

        $this->em = $em;
        $this->saveDone = $saveDone;
        $this->session = $session;
        //user
    }

    // $issueType = $manager->getReference('BlogCoreBundle:IssueType',1);
    public function save($exercise, $err_qui,$err_quand,$err_ou){
        //$this->session->set('exercise_data', $data);

        $saveDoneGeneral = $this->saveDone->add();
              

        $exerciseDone = new \ExerciseBundle\Entity\ExerciseDone();
        $exerciseDone
            ->setId($saveDoneGeneral)
            ->setExercise($exercise)
            ->setErrQui($err_qui)
            ->setErrQuand($err_quand)
            ->setErrOu($err_ou);

       
        

        $this->em->merge($exerciseDone);
        // Obligatoire pour assigner un id particulier, bizarre
        //$metadata = $this->em->getClassMetaData(get_class($exerciseDone));
        //$metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
        $this->em->flush();
    }

    
}