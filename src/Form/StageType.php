<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Stage;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-input']
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('typeStage', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: PFA, PFE, Observation']
            ])
            ->add('sujet', TextareaType::class, [
                'attr' => ['class' => 'form-input', 'rows' => 3]
            ])
            ->add('encadreur', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function(Employe $emp) {
                    return $emp->getNom() . ' ' . $emp->getPrenom();
                },
                'attr' => ['class' => 'form-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Stage::class,
        ]);
    }
}
