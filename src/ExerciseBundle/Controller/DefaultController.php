<?php

namespace ExerciseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use ExerciseBundle\Entity\Thumbnail;


/**
* @Route("/exercise/qui-quand-ou")
*/

class DefaultController extends Controller
{
    /**
     * @Route("/", name="accueil-exercise-eleve")
     */

    public function indexAction(){
        // Choix niveau difficulté
        return $this->render('ExerciseBundle:Default:index.html.twig');
    }

    /**
     * @Route("/start", name="start-exercise-eleve")
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

        // On sauvegarde le tout en session (via un service)
        $this->get('exercise.exercise_chosen')->save(array(
            'exercise' => $exercise,
            'thumbnails_qui' =>$thumbnails_qui,
            'thumbnails_quand' =>$thumbnails_quand,
            'thumbnails_ou' =>$thumbnails_ou,
        ));


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
     * @Route("/check",name="quiquandou_check")
     * @Method({"GET", "POST"})
     */
    public function checkAction(Request $request){

        $data = $this->get('exercise.exercise_chosen')->get();
        $exercise = $data['exercise'];

        // Comparer réponses données et réponses correctes

        if($request->getMethod() != 'POST'){
            // return $this->redirectToRoute('start-exercise-eleve');
            die('la methode n\'est pas POST');
        }

        $form_qui = $request->request->get('data-qui');
        $form_quand = $request->request->get('data-quand');
        $form_ou = $request->request->get('data-ou');

        // tmp
        /*$form_qui = 3;
        $form_quand = 1;
        $form_ou = 2;*/

        

        if(
            empty($form_qui) || !array_key_exists($form_qui,$data['thumbnails_qui']) ||
            empty($form_quand) || !array_key_exists($form_quand,$data['thumbnails_quand']) ||
            empty($form_ou) || !array_key_exists($form_ou,$data['thumbnails_ou']) 
        
        ){
            //echo "lala";
            // return $this->redirectToRoute('start-exercise-eleve');
            echo 'une des réponses est vide<br>';
            print_r("<br>form qui: ".$form_qui);
            print_r("<br>form quand: ".$form_quand);
            print_r("<br>form ou: ".$form_ou);

            die();
        }



        $correct_qui = $exercise->getQui();
        $correct_quand = $exercise->getQuand();
        $correct_ou = $exercise->getOu();

        $response_qui = $data['thumbnails_qui'][$form_qui];
        $response_quand = $data['thumbnails_quand'][$form_quand];
        $response_ou = $data['thumbnails_ou'][$form_ou];

        $verdict_qui = $correct_qui == $response_qui ? 0 : 1;
        $verdict_quand = $correct_quand == $response_quand ? 0 : 1;
        $verdict_ou = $correct_ou == $response_ou ? 0 : 1;


        // Renvoyer à la vue réponses données et correction
        // + message commentaire
        // + Boutons pour enchainer avec un autre exercise


        return $this->render('ExerciseBundle:Default:check.html.twig',array(
            'story' => $exercise,
            'response_qui' => $response_qui,
            'response_quand' => $response_quand,
            'response_ou' => $response_ou,
            'correct_qui' => $correct_qui,
            'correct_quand' => $correct_quand,
            'correct_ou' => $correct_ou,
            
        ));

    }


}
