<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Route("/login_verify", name="login_verify")
     * @Method({"GET", "POST"})
     */
    public function loginAction(Request $request){

        $em = $this->getDoctrine();
        $show_password = false;
        $form_username="";
        $error="";
      
        // Formulaire envoyé
        if($request->getMethod() == 'POST'){

            

            $form_username = $request->request->get('_username');
            $form_password = $request->request->get('_password');

            // Crypter mdp
            // .....

            $user = $em->getRepository('SchoolBundle:User')->findOneBy(array('username'=>$form_username)); 
            

            if(!$user){
                $error="Ce nom d'utilisateur n'existe pas.";
            }
            elseif(!empty($user->getPassword()) && empty($form_password)){
                $error="Cet utilisateur requiert un mot de passe.";
                $show_password=true;
            }
            elseif(!empty($user->getPassword()) && sha1($form_password) != $user->getPassword()){
               
                $error="Le mot de passe indiqué n'est pas valide.";
                $show_password=true;
            }
            else{
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->get('security.token_storage')->setToken($token);
                return $this->redirectToRoute('root');
            }

        }

        return $this->render('security/login.html.twig', array(            
            'last_username' => $form_username,
            'error'         => $error,
            'show_password' => $show_password,
        ));

    }

    /**
     * @Route("/logout", name="logout")     
     */
    public function logout(){

    }

    // A suppr
    public function _loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'user_nopassword' => $this->getParameter('user_nopassword'),
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }
}