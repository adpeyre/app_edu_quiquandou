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
            'types' =>array_flip(Thumbnail::getTypesList()),
            'thumbnails' => $thumbnails,
        ));
    }

    /**
     * Creates a new thumbnail entity.
     *
     * @Route("/new", name="admin_thumbnail_new")
     * @Route("/{id}/edit", name="admin_thumbnail_edit")
     * @Method({"GET", "POST"})
     */
    # http://symfony.com/doc/current/controller/upload_file.html
    public function newAction(Request $request,Thumbnail $thumbnail=null)
    {

        $imageForm = (count($request->files->all()) >= 1) ? $request->files->all()[\ExerciseBundle\Form\ThumbnailType::getPrefix()]['image'] : null;

        $deleteForm = null;

        // New thumbnail
        if(is_null($thumbnail )){
            $thumbnail = new Thumbnail();
        }
        else{
            $deleteForm = $this->createDeleteForm($thumbnail)->createView();
        }

        $options = array(
            'required' => 
                $request->get('_route') == 'admin_thumbnail_new' || 
                (
                    $request->get('_route') == 'admin_thumbnail_edit'
                    && !empty($imageForm)
                )
        );
       
        //print_r($request->files->all());
        
        $form = $this->createForm('ExerciseBundle\Form\ThumbnailType', $thumbnail,$options);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {



            $file = $imageForm;          


            $em = $this->getDoctrine()->getManager();
            $em->persist($thumbnail);
            $em->flush();


            // Move the file to the directory where thumbnails are stored
            if(!empty($file)){
                try{
                    $this->get('app.uploader')->upload(
                        $file,
                        array(
                            'filename'=>$thumbnail->getId(),
                            'target_dir'=>$this->getParameter('thumbnails_directory')
                        ) 
                        
                    );
                }
                catch(Exception $e){
                    $em->remove($thumbnail);
                    $em->flush();
                }
            }


            $msg = ($request->get('_route') == 'admin_thumbnail_new') ? "Une nouvelle vignette est ajoutée." : "La vignette a bien été modifiée";
            $this->get('session')->getFlashBag()->add('success', $msg);

            return $this->redirectToRoute('admin_thumbnail_index');
        }

        return $this->render('ExerciseBundle:thumbnail:new.html.twig', array(
            'thumbnail' => $thumbnail,
            'delete_form'=>$deleteForm,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a thumbnail entity.
     *
     // * @Route("/{id}", name="admin_thumbnail_show")
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
            try{
                $em->remove($thumbnail);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', "La vignette a été supprimée.");
            }
            catch(\Exception $e){
                $this->get('session')->getFlashBag()->add('error', "Impossible de supprimer. La vignette est utilisée dans une des histoires.");
            }
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
