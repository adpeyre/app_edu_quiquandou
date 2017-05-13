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
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $exercise = new Exercise();
        $form = $this->createForm('ExerciseBundle\Form\ExerciseType', $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($exercise);
            $em->flush();

            return $this->redirectToRoute('admin_exercise_show', array('id' => $exercise->getId()));
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
     * Displays a form to edit an existing exercise entity.
     *
     * @Route("/{id}/edit", name="admin_exercise_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Exercise $exercise)
    {
        $deleteForm = $this->createDeleteForm($exercise);
        $editForm = $this->createForm('ExerciseBundle\Form\ExerciseType', $exercise);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_exercise_edit', array('id' => $exercise->getId()));
        }

        return $this->render('exercise/edit.html.twig', array(
            'exercise' => $exercise,
            'edit_form' => $editForm->createView(),
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
