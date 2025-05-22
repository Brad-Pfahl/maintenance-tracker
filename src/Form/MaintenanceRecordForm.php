<?php

namespace App\Form;

use App\Entity\MaintenanceRecord;
use App\Entity\vehicle;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class MaintenanceRecordForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type')
            ->add('otherType', null, [
                'required' => false,])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date of Service',
                'html5' => true,])
            ->add('mileage')
            ->add('notes')
            ->add('vehicle', EntityType::class, [
                'class' => vehicle::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MaintenanceRecord::class,
        ]);
    }
}
