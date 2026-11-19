<?php

namespace App\Form;

use App\Entity\Prime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrimeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomPrime', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Prime de performance']
            ])
            ->add('montantMax', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'Montant max']
            ])
            ->add('formule', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input', 'rows' => 2, 'placeholder' => 'ex: Score * 1000']
            ])
            ->add('collectif', CheckboxType::class, [
                'required' => false,
                'label' => 'Prime collective ?'
            ])
            ->add('kpis', \Symfony\Bridge\Doctrine\Form\Type\EntityType::class, [
                'class' => \App\Entity\Kpi::class,
                'choice_label' => 'nomKpi',
                'multiple' => true,
                'expanded' => true,
                'label' => 'KPIs associés (Critères)',
                'attr' => ['class' => 'checkbox-grid']
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Prime::class,
        ]);
    }
}
