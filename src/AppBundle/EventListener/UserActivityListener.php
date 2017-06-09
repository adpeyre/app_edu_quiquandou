<?php


namespace AppBundle\EventListener;



class UserActivityListener
{
    
    protected $userActivity;

    public function __construct($userActivity)
    {
        $this->userActivity = $userActivity;
    
    }

    public function processUpdate()
    {
        $this->userActivity->update(new \DateTime());
        
    }
}