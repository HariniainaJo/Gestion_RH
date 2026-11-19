<?php

namespace App\Form;

use App\Entity\Groupe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomGroupe', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Équipe Alpha']
            ])
            ->add('typeGroupe', ChoiceType::class, [
                'choices' => [
                    'Service' => 'SERVICE',
                    'Équipe' => 'EQUIPE',
                    'Projet' => 'PROJET',
                ],
                'attr' => ['class' => 'form-input']
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Groupe::class,
        ]);
    }
}
