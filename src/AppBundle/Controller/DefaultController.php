<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;
use ExerciseBundle\Entity\Exercise;
use SchoolBundle\Entity\User;

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
        else{

            // Création compte root si innexistant
            

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('SchoolBundle:User')->findOneBy(array('role' => 'ROLE_TEACHER'));

            if(empty($user)){

                $user = new \SchoolBundle\Entity\User();
                $user
                    ->setUsername('root')
                    ->setFirstname('root')
                    ->setLastname('root')
                    ->setPassword( $this->getParameter('root_password') )
                    ->setRole('ROLE_TEACHER')   ;

                $em->persist($user);
                $em->flush();

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

    /**    
     * @Route("/admin/export", name="admin_export")
     */
    public function exportAction(Request $request){

        $em = $this->getDoctrine()->getManager();
      




        $form = $this->get('form.factory')->createNamedBuilder('export');
        $form->add('type',ChoiceType::class, array(
                'label'=>"Données souhaitées",
                'choices'=>array(
                    'Statistiques des élèves (1 semaine)' => 'stats_user_1w'  ,
                    'Statistiques des élèves (1 mois)' => 'stats_user_1m'  
                ),               
                

        ));
        $form->add('submit',SubmitType::class, array(
            'label_format' => "Exporter",
            'attr' => array('class' => 'btn btn-primary'),
        ));

        $exportForm = $form->getForm();

        $exportForm->handleRequest($request);

        if ($exportForm->isSubmitted() && $exportForm->isValid()){

            $selected = $exportForm->getData()['type'];
            
            if($selected == 'stats_user_1w')
                $period = 'P1W';
            elseif($selected == 'stats_user_1m'){
                $period = 'P1M';
            }
            
            // Traitement de l'export

            $usersList = $em->getRepository('SchoolBundle:User')->findAll();

            array_walk($usersList,function(&$u,$key,$period){
                
                $statsService = $this->get('exercise.stats_user');
                $u2 = array();
                $u2['infos']=$u;
                $u2['stats']=$statsService->getSummary(
                    $u,                   
                    (new \DateTime())->sub(  new \DateInterval($period)  ) 
                );
                $u = $u2;
            }, $period);


            $levelsAvailable = Exercise::getLevelsAvailable();
            
            $levelsAvailable['global']='global';

            $export = "id,nom,";

            foreach($levelsAvailable as $lname => $lval){                
                $export .= "exe_".$lname."_global,exe_".$lname."_qui,exe_".$lname."_quand,exe_".$lname."_ou,";
            }

            $export.='
            ';

            foreach($usersList as  $u){
                $export .= "".$u['infos']->getId().",".$u['infos']->getFullName().",";

                foreach($levelsAvailable as $lname => $lval){
                    $export .= "".$u['stats'][$lval]['score_global'].",".$u['stats'][$lval]['score_qui'].",".$u['stats'][$lval]['score_quand'].",".$u['stats'][$lval]['score_ou'].",";
                }

                $export.='
                ';
            }


            $response =  new Response();
            $response->setContent($export);
            

            // On indique un type mime que les navigateurs ne prennent pas en charge
            $response->headers->set('Content-Type', 'application/x-rar-compressed');
            $response->headers->set('Content-disposition', 'attachment; filename=data.csv');

            return $response;

            

        }



        // print_r($exercisesLastDoneWithScores);
        
        return $this->render('admin/export.html.twig',array(
           'form'=>$exportForm->createView()
            
        ));
    }



}
