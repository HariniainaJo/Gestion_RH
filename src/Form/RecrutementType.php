<?php

namespace App\Form;

use App\Entity\Poste;
use App\Entity\Recrutement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RecrutementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Date de début',
                'attr' => ['class' => 'form-input']
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'label' => 'Date de clôture prévue',
                'attr' => ['class' => 'form-input']
            ])
            ->add('poste', EntityType::class, [
                'class' => Poste::class,
                'choice_label' => 'libelle',
                'label' => 'Poste à pourvoir',
                'attr' => ['class' => 'form-input']
            ])
            ->add('suggestions', TextareaType::class, [
                'required' => false,
                'label' => 'Critères / Suggestions',
                'attr' => ['class' => 'form-input', 'rows' => 4]
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Ouvert' => 'Ouvert',
                    'Clôturé' => 'Clôturé',
                    'Suspendu' => 'Suspendu',
                ],
                'attr' => ['class' => 'form-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recrutement::class,

        ]);
    }
}
