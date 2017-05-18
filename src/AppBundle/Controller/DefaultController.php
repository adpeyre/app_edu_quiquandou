<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="root")
     */
    public function indexAction(Request $request)
    {
        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction(Request $request)
    {
        return $this->redirectToRoute('user_index');
    }
}
