<?php

namespace ExerciseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
* @Route("/exercise/qui-quand-ou")
*/

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */

    public function indexAction(){
        return $this->render('ExerciseBundle:Default:index.html.twig');
    }

    /**
     * @Route("/start")
     * @Method({"GET", "POST"})
     */
    public function startAction(){

        // Init exercise (via service ?)


        
        // récupérer un exercise (via un service)
        $exercise = $this->get('exercise.get_exercise')->getOne();

        // récupérer la liste des vignettes à afficher. La vraie est noyée autour de mauvaises(via un service)
        


        //On envoie à la vue tout ça


    }

    /**
     * @Route("/verify")
     * @Method({"GET", "POST"})
     */
    public function verifyAction(){

        // Comparer réponses données et réponses correctes


        // Renvoyer à la vue réponses données et correction
        // + message commentaire
        // + Boutons pour enchainer avec un autre exercise

    }


}
