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
    public function indexAction(Request $request){
        $sec = $this->get('security.authorization_checker');
        if ($sec->isGranted('IS_AUTHENTICATED_FULLY')){
            if($sec->isGranted('ROLE_TEACHER')){
                return $this->redirectToRoute('admin_index');
            }
            else{
                return $this->redirectToRoute('accueil-exercise-eleve');
            }
        }

         return $this->render('default/index.html.twig',array(
           
            
        ));
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
        $exercisesLastDone = $this->get('exercise.last_done')->getList(20);



        // print_r($exercisesLastDoneWithScores);
        
        return $this->render('admin/index.html.twig',array(
            'students_nb' => $users_nb,
            'exercises_nb' => $exercises_nb,
            'exercises_lastDone' => $exercisesLastDone
            
        ));
    }
}
