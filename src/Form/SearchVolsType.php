<?php

namespace App\Form;
use App\Repository\VolsRepository;
use App\Entity\Vols;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class SearchVolsType extends AbstractType
{   public function buildForm(FormBuilderInterface $builder, array $options): void
{
    $builder

        ->add('vols',EntityType::class,[
         'class' =>Vols::class,
         'choice_label'=> 'destination_aller'

       ])


        ->add('vols',EntityType::class,[
            'class' =>Vols::class,
            'choice_label'=> 'destination_retour'
        ])

        ->add('vols',EntityType::class,[
            'class' =>Vols::class,
            'choice_label'=> 'cabine'
        ])



        ->add('vols',EntityType::class,[
            'class' =>Vols::class,
            'choice_label'=> 'voyage'
        ])


       ->add('vols',DateType::class, [

            'class' =>Vols::class,
            'choice_label'=> 'date_depart'
        ])



        ->add('vols',DateType::class, [

                'class' =>Vols::class,
                'choice_label'=> 'date_retour'

        ])


        ->add('vols',EntityType::class,[
            'class' =>Vols::class,
            'choice_label'=> 'passagers'
        ])


    ->add('recherche',SubmitType::class)  ;
}

    public function configureOptions(OptionsResolver $resolver): void
   {/*

        $resolver->setDefaults([
            'data_class' => Vols::class,
        ]); */
    }
}
