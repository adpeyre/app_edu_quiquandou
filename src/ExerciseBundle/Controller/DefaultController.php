<?php

namespace ExerciseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;

use ExerciseBundle\Entity\Thumbnail;

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
        $thumbnails_qui = $this->get('exercise.mixing_thumbnails')->getThem(Thumbnail::QUI, $exercise->getQui());        
        $thumbnails_quand = $this->get('exercise.mixing_thumbnails')->getThem(Thumbnail::QUAND, $exercise->getQuand());
        $thumbnails_ou = $this->get('exercise.mixing_thumbnails')->getThem(Thumbnail::OU, $exercise->getOu());


        //On envoie à la vue tout ça

        return $this->render('ExerciseBundle:Default:resolve.html.twig',array(
            'story' => $exercise,
            'thumbnails_qui' => $thumbnails_qui,
            'thumbnails_quand' => $thumbnails_quand,
            'thumbnails_ou' => $thumbnails_ou,
            'thumbnails_directory' => $this->getParameter('thumbnails_directory_view'),
        ));


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
