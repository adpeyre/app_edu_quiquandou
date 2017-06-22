<?php

namespace ExerciseBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use ExerciseBundle\Entity\Exercise;
use ExerciseBundle\Entity\Thumbnail;


/**
* @Route("/exercise/qui-quand-ou")
*/

class DefaultController extends Controller
{
    /**
     * @Route("/", name="accueil-exercise-eleve")
     */

    public function indexAction(Request $request){

        $choices = Exercise::getLevelsAvailable();       

        $choices = array_map(function ($l){
            return $l;
        }, $choices);

         $data = $this->get('exercise.data');
         $data->resetSession();

        $form = $this->createFormBuilder();
        $form
        ->add('mode',ChoiceType::class, array(
             'choices'=> array(
                 'Automatique' => 0,
                 'Manuelle' => 1 
             ),
            'label_format' => "Difficulté",
            'data' => 0
        ))
        ->add('difficulty',ChoiceType::class, array(
            'choices'=> ($choices),
            'label_format' => "Difficulté des histoires",
            'data' => 0
        ))
        ->add('thumbnails_nb',ChoiceType::class, array(
            'choices'=> array(
                "3 vignettes" => 3,
                "4 vignettes" => 4,
                "5 vignettes" => 5,
                "6 vignettes" => 6,
            ),
            'label_format' => "Nombre de vignettes",
            'data' => 4
        ));

        $form= $form->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $form_data = $form->getData();
          
            // On va stocker en session les choix et on redirige vers la rouge de start

            $data = $this->get('exercise.data');
            $data->setMode($form_data['mode']);
            $data->setDifficulty($form_data['difficulty']);
            $data->setThumbnailsNb($form_data['thumbnails_nb']);

            return $this->redirectToRoute('start-exercise-eleve');
        }


        // Choix niveau difficulté
        return $this->render('ExerciseBundle:Default:index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/do", name="start-exercise-eleve")
     * @Method({"GET", "POST"})
     */
    public function doAction($responses=null){

       

         $data = $this->get('exercise.data');
        
         // Aucune difficulté choisie
         if($data->getMode() !== 0 && $data->getMode() !== 1 ){
             return $this->redirectToRoute('accueil-exercise-eleve');
         }


        
        
        try{
            // récupérer un exercise (via un service)
            $exercise = $this->get('exercise.get_exercise')->getOne();
        }
        catch(Exception $e){
            return $this->redirectToRoute('accueil-exercise-eleve');
        }

        // récupérer la liste des vignettes à afficher. La vraie est noyée autour de mauvaises(via un service)
        $thumbnails_qui = $this->get('exercise.mixing_thumbnails')->getThem(Thumbnail::QUI, $exercise->getQui());        
        $thumbnails_quand = $this->get('exercise.mixing_thumbnails')->getThem(Thumbnail::QUAND, $exercise->getQuand());
        $thumbnails_ou = $this->get('exercise.mixing_thumbnails')->getThem(Thumbnail::OU, $exercise->getOu());

        // On sauvegarde le tout en session (via un service)
       
        $data
            ->setExercise($exercise)
            ->setThumbnailsQui($thumbnails_qui)
            ->setThumbnailsQuand($thumbnails_quand)
            ->setThumbnailsOu($thumbnails_ou)
            ;

        
       
        
        // Retour après formulaire
        
        $wrong_thumb = $data->getWrongThumb();
        $right_thumb = array();
        if($exercise->getQui() == $responses['qui'])
            array_push($right_thumb,$responses['qui']);
        if($exercise->getQuand() == $responses['quand'])
            array_push($right_thumb,$responses['quand']);
        if($exercise->getOu() == $responses['ou'])
            array_push($right_thumb,$responses['ou']);
        
      
      
        // Terminé avec cet exercice : on reset
        if($ended = $data->exerciseIsEnded()){

            $this->get('exercise.save_result')->save($exercise,$data->getAttempts('qui'),$data->getAttempts('quand'),$data->getAttempts('ou'));
            $data->setNb(  $data->getNb() + 1 );
            

            $correct = array(
                'qui' => $exercise->getQui(),
                'quand' => $exercise->getQuand(),
                'ou' => $exercise->getOu(),
            );


            $data->clearCurrentExercise();

        }

        

        if(is_null($responses)){
            $responses = array(
                'qui' => null,
                'quand' =>null,
                'ou' => null
            );
        }

        if(empty($correct)){
            $correct = array(
                'qui' => null,
                'quand' =>null,
                'ou' => null
            );
        }




        //On envoie à la vue tout ça

        return $this->render('ExerciseBundle:Default:resolve.html.twig',array(
            'story' => $exercise,
            'thumbnails_qui' => $thumbnails_qui,
            'thumbnails_quand' => $thumbnails_quand,
            'thumbnails_ou' => $thumbnails_ou,   
            'wrong_thumb'=>$wrong_thumb,    
            'right_thumb'=>$right_thumb,     
            'responses'=>$responses,
            'correct'=>$correct,
            'ended'=>$ended
            
        ));


    }

    /**
     * @Route("/check",name="quiquandou_check")
     * @Method({"GET", "POST"})
     */
    public function checkAction(Request $request){

        $data = $this->get('exercise.data');
        
        $exercise = $data->getExercise();

       

        // Comparer réponses données et réponses correctes
        if($request->getMethod() != 'POST' || empty($exercise)){          
            
            return $this->redirectToRoute('start-exercise-eleve');
        }

        $form_qui = $request->request->get('qui');
        $form_quand = $request->request->get('quand');
        $form_ou = $request->request->get('ou');

        // Si aucune vignette choisie ou qu'elle n'est pas dans celles proposées : erreur
        if(
            empty($form_qui) || !array_key_exists($form_qui,$data->getThumbnailsQui()) ||
            empty($form_quand) || !array_key_exists($form_quand,$data->getThumbnailsQuand()) ||
            empty($form_ou) || !array_key_exists($form_ou,$data->getThumbnailsOu()) 
        
        ){
            
            return $this->redirectToRoute('start-exercise-eleve');
        }


        // Réponses attendues
        $correct_qui = $exercise->getQui();
        $correct_quand = $exercise->getQuand();
        $correct_ou = $exercise->getOu();

        // Réponses soumises
        $response_qui = $data->getThumbnailsQui()[$form_qui];
        $response_quand = $data->getThumbnailsQuand()[$form_quand];
        $response_ou = $data->getThumbnailsOu()[$form_ou];

        $wrong_thumb = $data->getWrongThumb();
        ;

        // qui : mauvaise réponse
        if($correct_qui != $response_qui){
            $data->setAttempts('qui',   $data->getAttempts('qui')+1  );
            array_push($wrong_thumb, $response_qui);
        }
        if($correct_quand != $response_quand){
            $data->setAttempts('quand',   $data->getAttempts('quand')+1  );
            array_push($wrong_thumb, $response_quand);
        }
        if($correct_ou != $response_ou){
            $data->setAttempts('ou',   $data->getAttempts('ou')+1  );
            array_push($wrong_thumb, $response_ou);
        }

        $data->setWrongThumb($wrong_thumb);
        //$data->setThumbLastSelected(array($response_qui,$response_quand,$response_ou));
        

        // Toutes les réponses sont bonnes
        if(($correct_qui == $response_qui && $correct_quand == $response_quand && $correct_ou == $response_ou) || $data->getAttempts() >= 1){            
            $data->setExerciseEnded(true);
        }

        $data->setAttempts('',  $data->getAttempts()+1   );

        //return $this->redirectToRoute('start-exercise-eleve');
        $response = $this->forward('ExerciseBundle:Default:do', array(
            'responses' => array(
                'qui'=>$response_qui,
                'quand'=>$response_quand,
                'ou' =>$response_ou
            )
            
        ));

        return $response;
        
        


        // Renvoyer à la vue réponses données et correction
        // + message imgaire
        // + Boutons pour enchainer avec un autre exercise


        return $this->render('ExerciseBundle:Default:check.html.twig',array(
            'story' => $exercise,
            'response_qui' => $response_qui,
            'response_quand' => $response_quand,
            'response_ou' => $response_ou,
            'correct_qui' => $correct_qui,
            'correct_quand' => $correct_quand,
            'correct_ou' => $correct_ou,
            'verdict_qui' => $verdict_qui,
            'verdict_quand' => $verdict_quand,
            'verdict_ou' => $verdict_ou,
            'verdict_total' => $verdict_total,
            
        ));

    }

    /**
     * @Route("/results", name="exercise-user-results")
     * @Method({"GET"})
     */
    public function resultsAction(){
        // Bilan

        $data = $this->get('exercise.data');

        if(empty($data->getNb()) ){
            return $this->redirectToRoute('accueil-exercise-eleve');
        }

        $stats=  $this->get('exercise.stats_user')->getSummary($this->getUser(),$data->getDateBegining());
        

        $exercises_nb = $data->getNb();
        $exercises_successful = $stats['global']['nb_successful'] ; // 
        $exercises_missed = $stats['global']['nb_done'] - $exercises_successful ; //

        $exercises_note = round($exercises_successful / $stats['global']['nb_done'] * 10);


        $exercisesDone = $this->get('exercise.last_done')->getList($data->getNb(),$this->getUser());


        return $this->render('ExerciseBundle:Default:results.html.twig',array(
            'exercises_nb' => $exercises_nb,
            'exercises_successful' => $exercises_successful,
            'exercises_missed' => $exercises_missed,
            'exercises_note' => $exercises_note,  
            'exercise_done' => $exercisesDone
              
            
        ));


    }

    public function navbarAction(){

        /*$choices = array_flip(Exercise::getLevelsAvailable());
        $choices[0] = "Automatique";
        $data = $this->get('exercise.data');
        $level = $data->getDifficulty();

        $html='';
        
        if($data->getMode() === 0){
            $html .='- Mode automatique -';
        }
        elseif($data->getMode() == 1 && !empty($choices[$level])){
            $html .= 'Mode manuel - Niveau : '.$choices[$level].' -';
        }
        
        
        if(!is_null($data->getMode())){
            $nb = intval($data->getNb());
            //$html .= " [".$nb." terminé".($nb>1 ? 's' : '')."]";
            $html.= " ".$nb;
        }
        */
        $data = $this->get('exercise.data');
        $dateBegining = $data->getDateBegining();
        
        $html='';

        $val =  $this->get('exercise.stats_user')->getProgessingValue($this->getUser(), $dateBegining);

         $html .='<div class="progress">
            <div class="progress-bar progress-bar-striped active" role="progressbar"
            aria-valuenow="'.$val.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$val.'%" title="'.$val.'%)">
                
            </div>
            </div>'; 

        return new Response($html);




    }


}
