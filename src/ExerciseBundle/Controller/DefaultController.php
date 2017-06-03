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
    public function startAction(){

         $data = $this->get('exercise.data');
        
         // Aucune difficulté choisie
         if($data->getMode() !== 0 && $data->getMode() !== 1 ){
             return $this->redirectToRoute('accueil-exercise-eleve');
         }


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
       
        $data
            ->setExercise($exercise)
            ->setThumbnailsQui($thumbnails_qui)
            ->setThumbnailsQuand($thumbnails_quand)
            ->setThumbnailsOu($thumbnails_ou)
            ;
       


        //On envoie à la vue tout ça

        return $this->render('ExerciseBundle:Default:resolve.html.twig',array(
            'story' => $exercise,
            'thumbnails_qui' => $thumbnails_qui,
            'thumbnails_quand' => $thumbnails_quand,
            'thumbnails_ou' => $thumbnails_ou,
            
        ));


    }

    /**
     * @Route("/check",name="quiquandou_check")
     * @Method({"GET", "POST"})
     */
    public function checkAction(Request $request){

        $data = $data = $this->get('exercise.data');
        
        $exercise = $data->getExercise();

       

        // Comparer réponses données et réponses correctes

        if($request->getMethod() != 'POST'){
            
            // die('la methode n\'est pas POST');
            
            // return $this->redirectToRoute('start-exercise-eleve');
        }

        $form_qui = $request->request->get('data-qui');
        $form_quand = $request->request->get('data-quand');
        $form_ou = $request->request->get('data-ou');

        // tmp
        /*$form_qui = 3;
        $form_quand = 1;
        $form_ou = 2;*/

        

        if(
            empty($form_qui) || !array_key_exists($form_qui,$data->getThumbnailsQui()) ||
            empty($form_quand) || !array_key_exists($form_quand,$data->getThumbnailsQuand()) ||
            empty($form_ou) || !array_key_exists($form_ou,$data->getThumbnailsOu()) 
        
        ){
            //echo "lala";
            
            echo 'une des réponses est vide<br>';
            print_r("<br>form qui: ".$form_qui);
            print_r("<br>form quand: ".$form_quand);
            print_r("<br>form ou: ".$form_ou);

            // die();

            return $this->redirectToRoute('start-exercise-eleve');
        }



        $correct_qui = $exercise->getQui();
        $correct_quand = $exercise->getQuand();
        $correct_ou = $exercise->getOu();

        $response_qui = $data->getThumbnailsQui()[$form_qui];
        $response_quand = $data->getThumbnailsQuand()[$form_quand];
        $response_ou = $data->getThumbnailsOu()[$form_ou];

        $verdict_qui = $correct_qui == $response_qui ? 1 : 0;
        $verdict_quand = $correct_quand == $response_quand ? 1 : 0;
        $verdict_ou = $correct_ou == $response_ou ? 1 : 0;

        if($verdict_ou && $verdict_quand && $verdict_qui) {
            $verdict_total = 1;
        }
        else
            $verdict_total = 0;

        
        $this->get('exercise.save_result')->save($exercise,$verdict_qui,$verdict_quand,$verdict_ou);


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
            'verdict_qui' => $verdict_qui,
            'verdict_quand' => $verdict_quand,
            'verdict_ou' => $verdict_ou,
            'verdict_total' => $verdict_total,
            
        ));

    }

    public function navbarAction(){

        $choices = array_flip(Exercise::getLevelsAvailable());
        $choices[0] = "Automatique";
        $data = $this->get('exercise.data');
        $level = $data->getDifficulty();

        $html='';
       
        if(!empty($choices[$level])){
            $html .= '- Niveau : '.$choices[$level].' -';
        }

        return new Response($html);




    }


}
