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
     * @Route("/admin", name="admin") // deprecied
     * @Route("/admin", name="admin_index")
     */
    public function adminAction(Request $request){

        $em = $this->getDoctrine()->getManager();
        $users_nb = $em->getRepository('SchoolBundle:User')->getNbUsers();
        $exercises_nb = $em->getRepository('ExerciseBundle:Exercise')->getNbGlobal();

        // Derniers exercices effectués (qui quand ou)
        $exercisesLastDone = $em->getRepository('ExerciseBundle:ExerciseDone')->getLastDone();
        //print_r($exercisesLastDone);
        
        return $this->render('admin/index.html.twig',array(
            'students_nb' => $users_nb,
            'exercises_nb' => $exercises_nb,
            'exercises_lastDone' => $exercisesLastDone
            
        ));
    }
}
