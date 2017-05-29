<?php

namespace SchoolBundle\Controller;

use SchoolBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use SchoolBundle\Entity\Classroom;
use SchoolBundle\Entity\ClassAssignment;
use Symfony\Component\Validator\Constraints\Choice;
/**
 * User controller.
 *
 * @Route("admin/user")
 */
class UserController extends Controller
{
    /**
     * Lists all user entities.
     *
     * @Route("/", name="user_index")
     * @Route("/class/{class_id}", name="user_class", requirements={"id": "\d+"})
     * @Method("GET")
     */
    public function indexAction($class_id=0)
    {
        $em = $this->getDoctrine()->getManager();

        $classroom = null;
        
        if(!empty($class_id)){
            $result = $em->getRepository('SchoolBundle:Classroom')->getUsersByClass($class_id);
            $classroom = array_shift($result);          
            $users=$result;
        }
        else{
            $users = $em->getRepository('SchoolBundle:User')->findAll();
        }          

        return $this->render('SchoolBundle:user:index.html.twig', array(
            'classroom' => $classroom,
            'users' => $users,
        ));
    }

    /**
     * Creates a new user entity.
     *
     * @Route("/new", name="user_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm('SchoolBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('success', 'Un nouvel utilisateur a été ajouté.');

            return $this->redirectToRoute('user_show', array('id' => $user->getId()));
        }

        return $this->render('SchoolBundle:user:new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     * @Route("/{id}", name="user_show")
     * @Method("GET")
     */
    public function showAction(User $user)
    {


        $stats_user = $this->get('exercise.stats_user')->getSummary($user);
        
        // de-commenter la ligne pour voir les stats
        // echo'<pre>'; print_r($stats_user); echo'</pre>';

        $deleteForm = $this->createDeleteForm($user);

        return $this->render('SchoolBundle:user:show.html.twig', array(
            'user' => $user,
            'stats_user' => $stats_user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/{id}/edit", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        $editGeneralForm = $this->createForm('SchoolBundle\Form\UserType', $user);
        $editGeneralForm->handleRequest($request);

        

        if ($editGeneralForm->isSubmitted() && $editGeneralForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            
            $this->get('session')->getFlashBag()->add('success', "Les informations sur cet utilisateur ont bien été modifiées.");

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }


        


        $em = $this->getDoctrine()->getManager();
        $classrooms = $em->getRepository('SchoolBundle:Classroom')->getClassesAffected($user);
        //echo'<pre>';print_r($classrooms);echo'</pre>';


        $bdd_classesIn = array();
        $year_choices = array();
        foreach($classrooms as $classroom){    

            $class = $classroom['classroom']; 
            $class_assignment  = $classroom['class_assignment']; 
           
            $year_choices[$class->getYear()]['choices'][$class->getId()] = $class->getName();

            $year_choices[$class->getYear()]['default']= empty($year_choices[$class->getYear()]['default']) && !$class_assignment ? 0 : $class->getId();

            if($class_assignment){
                array_push( $bdd_classesIn, $class->getId());
            }
            
        }


        



        $form = $this->createFormBuilder();

        foreach($year_choices as $year => $classes){
            $classes_choices = $classes['choices'];
            $classes_choices[0] = "Aucune classe";
            
            $form->add($year,ChoiceType::class, array(
                'label'=>"Année ".$year,
                'choices'=>array_flip($classes_choices),
                'data' => $classes['default'],
                'constraints' => array(
                    new Choice(
                        array(
                            'choices' => array_keys($classes_choices)
                        )
                    )
                )

            ));
        }

        $editClassesform = $form->getForm();

        $editClassesform->handleRequest($request);

        $form_classesIn = array();



         if ($editClassesform->isSubmitted() && $editClassesform->isValid()){
            
             foreach(array_keys($year_choices) as $year){   
                $val = $editClassesform->get($year)->getData(); 
                if(!empty($val)){            
                    array_push( $form_classesIn, $val);
                }
             }

             
             
           // A supprimer de la bdd
           $del = array_diff($bdd_classesIn, $form_classesIn);
          
            foreach($del as $class_id){               
                $em->remove( $em->getRepository('SchoolBundle:ClassAssignment')->findOneBy(array('class'=>$class_id,'user'=>$user)) );               
                $em->flush();               
            }
            

            // Association à ajouter en bdd
            $add = array_diff($form_classesIn,$bdd_classesIn );            
            foreach($add as $class_id){               

                $classAssignment = new ClassAssignment();
                $classAssignment->setClass(   $classrooms[$class_id]['classroom']   );
                $classAssignment->setUser($user);
                
                $em->persist($classAssignment);
                $em->flush();
            }

           
            $this->get('session')->getFlashBag()->add('success', "Les modifications concernant la classe de cet éléve ont bien été prises en compte.");

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));

         }


        
        
        




        return $this->render('SchoolBundle:user:edit.html.twig', array(
            'user' => $user,
            'edit_general_form' => $editGeneralForm->createView(),
            'edit_class_form' => $editClassesform->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('user_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
