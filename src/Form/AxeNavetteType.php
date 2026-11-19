<?php

namespace App\Form;

use App\Entity\AxeNavette;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AxeNavetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomAxe', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Axe Nord']
            ])
            ->add('pointDepart', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Analamahitsy']
            ])
            ->add('pointArrivee', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Siège Zanova']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AxeNavette::class,
        ]);
    }
}
