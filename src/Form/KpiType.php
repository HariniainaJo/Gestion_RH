<?php

namespace App\Form;

use App\Entity\Kpi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KpiType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomKpi', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Ponctualité']
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input', 'rows' => 3]
            ])
            ->add('valeurMin', NumberType::class, [
                'attr' => ['class' => 'form-input']
            ])
            ->add('valeurMax', NumberType::class, [
                'attr' => ['class' => 'form-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Kpi::class,
        ]);
    }
}
