<?php

namespace SchoolBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ClassroomType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $year_suggestion=array();
        $year_default="";
        for($y=date('Y')-1; $y<date('Y')+2; $y++){            
            $year_suggestion['Année scolaire '.$y.'-'.($y+1)] = $y.'-'.($y+1);
            if($y == date('Y'))
                $year_default = $y.'-'.($y+1);
        }

        

        $builder
        ->add('name')
        ->add('year', ChoiceType::class, array(
            'choices' => $year_suggestion,
            'data' => $year_default
        ));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'SchoolBundle\Entity\Classroom'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'schoolbundle_classroom';
    }


}
