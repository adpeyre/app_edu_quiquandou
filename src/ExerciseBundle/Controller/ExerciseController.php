<?php

namespace ExerciseBundle\Controller;

use ExerciseBundle\Entity\Exercise;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Exercise controller.
 *
 * @Route("admin/exercise")
 */
class ExerciseController extends Controller
{
    /**
     * Lists all exercise entities.
     *
     * @Route("/", name="admin_exercise_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $exercises = $em->getRepository('ExerciseBundle:Exercise')->findAll();

        return $this->render('ExerciseBundle:admin_exercise:index.html.twig', array(
            'exercises' => $exercises,
        ));
    }

    /**
     * Creates a new exercise entity.
     *
     * @Route("/new", name="admin_exercise_new")
     * @Route("/{id}/edit", name="admin_exercise_edit")
     * @Method({"GET","POST"})
     */
    public function newAction(Request $request, Exercise $exercise=null)
    {
        
        if(is_null($exercise))
            $exercise = new Exercise();
        

        // On garde en mémoire l'ancien fichier si il y a changement
        $exercise_old = clone $exercise;
        

        $form = $this->createForm('ExerciseBundle\Form\ExerciseType', $exercise);
       
        $form->handleRequest($request);
        

        if ($form->isSubmitted() && $form->isValid()) {
           
            $em = $this->getDoctrine()->getManager();

            // Demande de supprimer enregistrement audio
            $sound_delete = $form->has('sound_delete') ? $form->get('sound_delete')->getData() : false;
      
            
            if($sound_delete){                  
                $exercise->setSound(null);                
            }

            // Si aucun audio n'est soumis et qu'il n'y en avait pas avant : on génère le son automatiquement
            if(
                empty($exercise->getFile()) && // Pas de soumis
                (empty($exercise_old->getSound()) || $exercise_old->isSoundAuto()  ) // Aucun avant ou un automatique
                
            ){
                $fileName = empty($exercise_old->getSound()) ? 'auto_'.uniqid().'.mp3' : $exercise_old->getSound();
                $url="http://api.voicerss.org/?key=ffd188fbb5da4474b5d9538ddd355ed1&hl=fr-fr&r=0&src=".urlencode($exercise->getText());                
                file_put_contents($exercise->getSoundRootDir().'/'.$fileName, fopen($url, 'r'));
                
                $exercise->setSound($fileName);

            }
            

            /*
            // Si aucun audio n'est soumis et qu'il n'y en avait pas avant : on génère le son automatiquement
            if(
                empty($sound_file) && // Pas de soumis
                (empty($ex_sound_file) || preg_match('/auto/',$ex_sound_file)  ) // Aucun avant ou un automatique
                
            ){
                $fileName = empty($ex_sound_file) ? 'auto_'.uniqid().'.mp3' : $ex_sound_file;
                $url="http://api.voicerss.org/?key=ffd188fbb5da4474b5d9538ddd355ed1&hl=fr-fr&r=0&src=".urlencode($exercise->getText());
                file_put_contents($this->getParameter('sound_directory').'/'.$fileName, fopen($url, 'r'));
                $exercise->setSound($fileName);

            }
            // Si rien n'est soumis et qu'on demande pas à supprimer
            elseif(empty($sound_file) && !$sound_delete){
                $exercise->setSound($ex_sound_file);
            }
            // Demande de suppression
            elseif($sound_delete){             
                if(file_exists($this->getParameter('sound_directory').'/'.$ex_sound_file))   
                    unlink($this->getParameter('sound_directory').'/'.$ex_sound_file);
                $exercise->setSound(null);                
            }
            // Nouvel enregistrement
            else{            
               
                $ext_info = new \SplFileInfo($sound_file->getClientOriginalName());              

                // Generate a unique name for the file before saving it                
                $sound_fileName =uniqid().'.'.$ext_info->getExtension();
                
                // Move the file to the directory where brochures are stored
                $sound_file->move(
                    $this->getParameter('sound_directory'),
                    $sound_fileName
                );

                $exercise->setSound($sound_fileName);

                // Supprimer ancien enregistrement
                if(!empty($ex_sound_file))
                    unlink($this->getParameter('sound_directory').'/'.$ex_sound_file);

            }

            */
            
            $em->persist($exercise);
            $em->flush();

            if($request->get('_route') == 'admin_exercise_new'){
                $msg = "Une nouvelle histoire a bien été ajoutée.";
            }
            else{
                $msg = "L'histoire a bien été modifiée.";
            }

            $this->get('session')->getFlashBag()->add('success', $msg);

            return $this->redirectToRoute('admin_exercise_index');
        }

        return $this->render('ExerciseBundle:admin_exercise:new.html.twig', array(
            'exercise' => $exercise,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a exercise entity.
     *
     * @Route("/{id}", name="admin_exercise_show")
     * @Method("GET")
     */
    public function showAction(Exercise $exercise)
    {

        return $this->redirectToRoute('admin_exercise_edit', array(
            'id' => $exercise->getId()
        ));


       
    }


    /**
     * Deletes a exercise entity.
     *
     * @Route("/{id}/delete", name="admin_exercise_delete")
     * @Method("GET")
     */
    public function deleteAction(Exercise $exercise)
    {
      
        $em = $this->getDoctrine()->getEntityManager();       
        
        if(!empty($exercise)){
            $em->remove($exercise);
            $em->flush();
        }

       
        $this->get('session')->getFlashBag()->add('success', "Histoire supprimée avec succès.");
        

        return $this->redirectToRoute('admin_exercise_index');
    }

    
    /**
     * Active/Desactive a exercise entity.
     *
     * @Route("/{id}/toggle", name="admin_exercise_toggle")
     * @Method("GET")
     */
    public function toggleAction(Request $request, Exercise $exercise)
    {
       
        $em = $this->getDoctrine()->getManager();
        if($exercise->isActive()){
            $exercise->setActive(false);
            $this->get('session')->getFlashBag()->add('success', "L'histoire a été desactivée.");
        }
        else{
            $exercise->setActive(true);
            $this->get('session')->getFlashBag()->add('success', "L'histoire est de nouveau active.");
        }

        $em->persist($exercise);
        $em->flush();       
        

        return $this->redirectToRoute('admin_exercise_index');
    }

    /**
     * Creates a form to delete a exercise entity.
     *
     * @param Exercise $exercise The exercise entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Exercise $exercise)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_exercise_delete', array('id' => $exercise->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
