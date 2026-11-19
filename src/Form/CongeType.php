<?php

namespace App\Form;

use App\Entity\Conge;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CongeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-input']
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-input']
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-input']
            ])
            ->add('typeConge', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: Annuel, Maladie, etc.']
            ])
            ->add('motif', TextareaType::class, [
                'attr' => ['class' => 'form-input', 'rows' => 3]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Conge::class,
        ]);
    }
}
