<?php

namespace ExerciseBundle\Controller;

use ExerciseBundle\Entity\Exercise;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

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
        $ex_sound_file = empty($exercise->getSound()) ? null : $exercise->getSound();
        

        $form = $this->createForm('ExerciseBundle\Form\ExerciseType', $exercise);
       
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            // Demande de supprimer enregistrement audio
            $sound_delete = $form->has('sound_delete') ? $form->get('sound_delete')->getData() : false;
      
            // Nouveau fichier d'enregistrement soumis
            $sound_file = $exercise->getSound();

            // Si rien n'est soumis et qu'on demande pas à supprimer
            if(empty($sound_file) && !$sound_delete){

            }
            // Demande de suppression
            elseif($sound_delete){                
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

            $em->persist($exercise);
            $em->flush();

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
        $deleteForm = $this->createDeleteForm($exercise);

        return $this->render('exercise/show.html.twig', array(
            'exercise' => $exercise,
            'delete_form' => $deleteForm->createView(),
        ));
    }


    /**
     * Deletes a exercise entity.
     *
     * @Route("/{id}", name="admin_exercise_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Exercise $exercise)
    {
        $form = $this->createDeleteForm($exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($exercise);
            $em->flush();
        }

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
