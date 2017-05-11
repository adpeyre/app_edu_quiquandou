<?php

namespace SchoolBundle\Controller;

use SchoolBundle\Entity\Classroom;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Classroom controller.
 *
 * @Route("admin/classroom")
 */
class ClassroomController extends Controller
{
    /**
     * Lists all classroom entities.
     *
     * @Route("/", name="admin_classroom_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $classrooms = $em->getRepository('SchoolBundle:Classroom')->findAll();

        return $this->render('SchoolBundle:classroom:index.html.twig', array(
            'classrooms' => $classrooms,
        ));
    }

    /**
     * Creates a new classroom entity.
     *
     * @Route("/new", name="admin_classroom_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $classroom = new Classroom();
        $form = $this->createForm('SchoolBundle\Form\ClassroomType', $classroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($classroom);
            $em->flush();

            return $this->redirectToRoute('admin_classroom_index');
        }

        return $this->render('SchoolBundle:classroom:new.html.twig', array(
            'classroom' => $classroom,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a classroom entity.
     *
     * @Route("/{id}", name="admin_classroom_show")
     * @Method("GET")
     */
    public function showAction(Classroom $classroom)
    {
        $deleteForm = $this->createDeleteForm($classroom);

        return $this->render('classroom/show.html.twig', array(
            'classroom' => $classroom,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing classroom entity.
     *
     * @Route("/{id}/edit", name="admin_classroom_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Classroom $classroom)
    {
        $deleteForm = $this->createDeleteForm($classroom);
        $editForm = $this->createForm('SchoolBundle\Form\ClassroomType', $classroom);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_classroom_edit', array('id' => $classroom->getId()));
        }

        return $this->render('SchoolBundle:classroom:edit.html.twig', array(
            'classroom' => $classroom,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a classroom entity.
     *
     * @Route("/{id}", name="admin_classroom_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Classroom $classroom)
    {
        $form = $this->createDeleteForm($classroom);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($classroom);
            $em->flush();
        }

        return $this->redirectToRoute('admin_classroom_index');
    }

    /**
     * Creates a form to delete a classroom entity.
     *
     * @param Classroom $classroom The classroom entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Classroom $classroom)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_classroom_delete', array('id' => $classroom->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
