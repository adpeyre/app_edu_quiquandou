<?php

namespace SchoolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $default_year = date('Y') - 7;
        
        $builder
        ->add('username',TextType::class, array(
            'label_format' => "Nom d'utilisateur"
        ))
        ->add('firstname',TextType::class, array(
            'label_format' => "Prénom"
        ))
        ->add('lastname',TextType::class, array(
            'label_format' => "Nom"
        ))
        ->add('birthDate', BirthdayType::class, array(
            'label_format' => "Date de naissance",
            'format' => 'dd-MM-yyyy',
            'data' => new \DateTime('01-01-'.$default_year)
        ))
        ->add('email',EmailType::class, array(
            'label_format' => "Adresse e-mail",
            'required' => false
        ))
        ->add('password', PasswordType::class,array(
            'label_format' => "Nouveau mot de passe",
            'required' => false
        ))
        ->add('role', ChoiceType::class,array(
            
            'choices'=> array(
                 'Enseignant' => "ROLE_TEACHER",
                 'Elève' => ""
            ),
            'data' => ""

           
        ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SchoolBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'school_user';
    }


}
