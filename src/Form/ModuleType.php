<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\MeasurementUnit;
use App\Entity\Module;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class ModuleType extends AbstractType
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
            ->add('unit', EntityType::class, [
                'class' => MeasurementUnit::class,
                'label' => 'Measurement unit:',
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('save', SubmitType::class, ['label' => 'Save']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Module::class,
        ]);
    }
}