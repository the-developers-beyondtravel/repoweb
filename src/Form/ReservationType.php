<?php

namespace App\Form;

use App\Entity\Guidetouristique;
use App\Entity\Hotels;
use App\Entity\Offre;
use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\Voitures;
use App\Entity\Vol;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date')
            ->add('id_offre', EntityType::class,[
                'class'=>Offre::class, 'choice_label'=>'destination'
            ])
            ->add('id_vol', EntityType::class,[
                'class'=>Vol::class, 'choice_label'=>'destination'
            ])
            ->add('id_guidetouristique', EntityType::class,[
                'class'=>Guidetouristique::class, 'choice_label'=>'nom'
            ])
            ->add('id_voiture', EntityType::class,[
                'class'=>Voitures::class, 'choice_label'=>'matricule'
            ])
            ->add('id_hotel', EntityType::class,[
                'class'=>Hotels::class, 'choice_label'=>'nom'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
