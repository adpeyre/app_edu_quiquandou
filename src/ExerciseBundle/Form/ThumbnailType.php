<?php

namespace ExerciseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Image;


use ExerciseBundle\Entity\Thumbnail;

class ThumbnailType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        

        
        $constraints = array(
            new NotBlank(array('message'=>"Aucune image spécifiée")),
            new Image(
                array(
                    'maxSize' => "500k",
                    'maxSizeMessage' => "L'image ne doit pas exéder un poids de {{limit}}",
                    'mimeTypes'=>array("image/jpeg", "image/png", "image/gif"),
                    'mimeTypesMessage' => "Format invalide. Merci de choisir une image jpeg,png ou gif."
                )
            ),                
        );

        if(isset($options['required']) && $options['required'] == false){
            $constraints = array();
        }

        $builder
        ->add('name')
        ->add('type', ChoiceType::class, array(
            'choices' => Thumbnail::getTypesList()
        ))

        ->add('image', FileType::class, array(           
            'mapped'=>false,
            'required'=>false,
            'constraints' => $constraints
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ExerciseBundle\Entity\Thumbnail'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return self::getPrefix();
    }

    public static function getPrefix(){
        return 'exercisebundle_thumbnail';
    }


}
