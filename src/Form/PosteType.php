<?php

namespace App\Form;

use App\Entity\Direction;
use App\Entity\Poste;
use App\Entity\Service;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PosteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Développeur PHP']
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input', 'rows' => 3]
            ])
            ->add('salaireBase', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => '0.00']
            ])
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'nomService',
                'required' => false,
                'placeholder' => 'Choisir un service (Optionnel)',
                'attr' => ['class' => 'form-input']
            ])
            ->add('direction', EntityType::class, [
                'class' => Direction::class,
                'choice_label' => 'nomDirection',
                'required' => false,
                'placeholder' => 'Choisir une direction (Optionnel)',
                'attr' => ['class' => 'form-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Poste::class,
        ]);
    }
}
