<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\MeasurementUnit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class MeasurementUnitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Add name:',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('designation', TextType::class, [
                'label' => 'Add designation:',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MeasurementUnit::class,
        ]);
    }
}