<?php

namespace ExerciseBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

use ExerciseBundle\Entity\Exercise;
use ExerciseBundle\Entity\Thumbnail;
class ExerciseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // \AppBundle\Entity\Entity2Repository
        $entityType =  array(
            'label' => "Qui ?",
           'class' => 'ExerciseBundle:Thumbnail',
           'query_builder' => function(EntityRepository $er){
               return $er->getQui();
           }
        );
        $builder
        ->add('title', TextType::class, array(
            'label'=> "Titre de l'histoire"
        ))
        ->add('text', TextareaType::class, array(
            'label'=> "Texte de l'histoire"
        ))
        ->add('level',ChoiceType::class, array(
            'label'=>"Niveau",
            'choices'=>Exercise::getLevelsAvailable()

        ))
       ->add('qui', EntityType::class, array(
            'label' => "Qui ?",
           'class' => 'ExerciseBundle:Thumbnail',
           'query_builder' => function(\ExerciseBundle\Repository\ThumbnailRepository  $er){
               return $er->getQui();
           }
        ))
       ->add('quand', EntityType::class,array(
            'label' => "Quand ?",
           'class' => 'ExerciseBundle:Thumbnail',
           'query_builder' => function(\ExerciseBundle\Repository\ThumbnailRepository  $er){
               return $er->getQuand();
           }
        ))
       ->add('ou', EntityType::class,array(
            'label' => "Où ?",
           'class' => 'ExerciseBundle:Thumbnail',
           'query_builder' => function(\ExerciseBundle\Repository\ThumbnailRepository  $er){
               return $er->getOu();
           }
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ExerciseBundle\Entity\Exercise'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'exercisebundle_exercise';
    }


}
