<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class VehicleForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('make')
            ->add('model')
            ->add('year', TextType::class, [
                'label' => 'Year',
                'attr' => ['maxlength' => 4, 'placeholder' => 'e.g. 2015'],
            ])
            ->add('vin')
            ->add('mileage', IntegerType::class, [
                'label' => 'Mileage',
                'attr' => [
                    'placeholder' => 'e.g. 10000',
                    'min' => 0,
                    'step' => 1,
                    'inputmode' => 'numeric',
                    'pattern' => '[0-9]*',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicle::class,
        ]);
    }
}
