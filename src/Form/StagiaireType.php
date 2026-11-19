<?php

namespace App\Form;

use App\Entity\Stagiaire;
use App\Form\StageType;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StagiaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'Nom']
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'Prénom']
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'email@exemple.com']
            ])
            ->add('contact', TextType::class, [
                'attr' => ['class' => 'form-input']
            ])
            ->add('etablissement', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Université de ...']
            ])
            ->add('niveau', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Master 2']
            ])
            ->add('currentStage', StageType::class, [
                'label' => false,
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stagiaire::class,
        ]);
    }
}
