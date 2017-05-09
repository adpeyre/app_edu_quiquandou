<?php

namespace SchoolBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/school")
     */
    public function indexAction()
    {
        return $this->render('SchoolBundle:Default:index.html.twig');
    }
}
