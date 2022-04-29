<?php

namespace App\Form;

use App\Entity\Chambres;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Hotels;

class ChambresType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeChambre')
            ->add('nbrLit')
            ->add('description')
            ->add('prix')
            ->add('hotels', EntityType::class, [
                'class' => Hotels::class,
                'choice_label' => 'nom',
                'multiple'=> false,
                'expaned' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Chambres::class,
        ]);
    }
}
