<?php

namespace ExerciceBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


/**
* @Route("/exercice/qui-quand-ou")
*/

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */

    public function indexAction(){
        return $this->render('ExerciceBundle:Default:index.html.twig');
    }

    /**
     * @Route("/start")
     * @Method({"GET", "POST"})
     */
    public function startAction(){

        // Init exercice (via service ?)



        // récupérer un exercice (via un service)


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
        // + Boutons pour enchainer avec un autre exercice

    }


}
