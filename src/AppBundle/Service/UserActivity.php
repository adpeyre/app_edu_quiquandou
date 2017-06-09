<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;

class UserActivity
{

    private $em;
    private $user;


    public function __construct(EntityManager $em,$user){

        $this->em = $em;
        $this->user=$user;     
  
    }


    public function update($date){
        
        if(!empty($this->user)){
            $user = $this->user->getUser();
            if(is_object($user)){
                $user->setActivity($date);
                $this->em->persist($user);
                $this->em->flush();
            }
        }
    }
}