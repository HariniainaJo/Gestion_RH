<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\PresenceRh;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PresenceRhType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-input']
            ])
            ->add('typeEvenement', ChoiceType::class, [
                'choices' => [
                    'Absence' => 'ABSENCE',
                    'Retard' => 'RETARD',
                ],
                'attr' => ['class' => 'form-input']
            ])
            ->add('dateEvenement', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-input']
            ])
            ->add('justification', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input', 'rows' => 3]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PresenceRh::class,
        ]);
    }
}
