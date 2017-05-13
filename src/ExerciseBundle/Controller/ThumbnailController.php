<?php

namespace ExerciseBundle\Controller;

use ExerciseBundle\Entity\Thumbnail;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Thumbnail controller.
 *
 * @Route("admin/thumbnail")
 */
class ThumbnailController extends Controller
{
    /**
     * Lists all thumbnail entities.
     *
     * @Route("/", name="admin_thumbnail_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $thumbnails = $em->getRepository('ExerciseBundle:Thumbnail')->findAll();

        return $this->render('ExerciseBundle:thumbnail:index.html.twig', array(
            'thumbnails_directory' => $this->getParameter('thumbnails_directory_view'),
            'thumbnails' => $thumbnails,
        ));
    }

    /**
     * Creates a new thumbnail entity.
     *
     * @Route("/new", name="admin_thumbnail_new")
     * @Method({"GET", "POST"})
     */
    # http://symfony.com/doc/current/controller/upload_file.html
    public function newAction(Request $request)
    {
        $thumbnail = new Thumbnail();
        $form = $this->createForm('ExerciseBundle\Form\ThumbnailType', $thumbnail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $thumbnail->getImage();

            // Generate a unique name for the file before saving it
            $fileName = md5(uniqid()).'.'.$file->guessExtension();

            // Move the file to the directory where thumbnails are stored
            $file->move(
                $this->getParameter('thumbnails_directory'),
                $fileName
            );

            // Update the 'thumbnail' property to store the image file name
            // instead of its contents
            $thumbnail->setImage($fileName);


            $em = $this->getDoctrine()->getManager();
            $em->persist($thumbnail);
            $em->flush();

            return $this->redirectToRoute('admin_thumbnail_show', array('id' => $thumbnail->getId()));
        }

        return $this->render('ExerciseBundle:thumbnail:new.html.twig', array(
            'thumbnail' => $thumbnail,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a thumbnail entity.
     *
     * @Route("/{id}", name="admin_thumbnail_show")
     * @Method("GET")
     */
    public function showAction(Thumbnail $thumbnail)
    {
        $deleteForm = $this->createDeleteForm($thumbnail);

        return $this->render('ExerciseBundle:thumbnail:show.html.twig', array(
            'thumbnail' => $thumbnail,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing thumbnail entity.
     *
     * @Route("/{id}/edit", name="admin_thumbnail_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Thumbnail $thumbnail)
    {
        $deleteForm = $this->createDeleteForm($thumbnail);
        $editForm = $this->createForm('ExerciseBundle\Form\ThumbnailType', $thumbnail);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_thumbnail_edit', array('id' => $thumbnail->getId()));
        }

        return $this->render('ExerciseBundle:thumbnail:edit.html.twig', array(
            'thumbnail' => $thumbnail,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a thumbnail entity.
     *
     * @Route("/{id}", name="admin_thumbnail_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Thumbnail $thumbnail)
    {
        $form = $this->createDeleteForm($thumbnail);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($thumbnail);
            $em->flush();
        }

        return $this->redirectToRoute('admin_thumbnail_index');
    }

    /**
     * Creates a form to delete a thumbnail entity.
     *
     * @param Thumbnail $thumbnail The thumbnail entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Thumbnail $thumbnail)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('admin_thumbnail_delete', array('id' => $thumbnail->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
