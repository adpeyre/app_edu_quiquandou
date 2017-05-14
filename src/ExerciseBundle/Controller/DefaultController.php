<?php

namespace ExerciseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

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


        try{
            // récupérer un exercise (via un service)
            $exercise = $this->get('exercise.get_exercise')->getOne();
        }
        catch(Exception $e){
            echo "Aucun exercice trouvé";
        }

        

        // récupérer la liste des vignettes à afficher. La vraie est noyée autour de mauvaises(via un service)
        $thumbnails_qui = $this->get('exercise.mixing_thumbnails')->getThem(1, $exercise->getQui());        
        $thumbnails_quand = $this->get('exercise.mixing_thumbnails')->getThem(2, $exercise->getQuand());
        $thumbnails_ou = $this->get('exercise.mixing_thumbnails')->getThem(3, $exercise->getOu());


        //On envoie à la vue tout ça

        foreach($thumbnails_qui as $thumb){
            echo $thumb->getName().'<br />';
        }


        return new Response("test".$exercise->getTitle());


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
