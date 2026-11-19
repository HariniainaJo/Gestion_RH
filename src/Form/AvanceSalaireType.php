<?php

namespace App\Form;

use App\Entity\AvanceSalaire;
use App\Entity\Employe;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AvanceSalaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('employe', EntityType::class, [
                'class' => Employe::class,
                'choice_label' => function(Employe $emp) {
                    return $emp->getNom() . ' ' . $emp->getPrenom();
                },
                'attr' => ['class' => 'form-input']
            ])
            ->add('montant', NumberType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'Montant en Ar']
            ])
            ->add('dateDemande', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-input']
            ])
            ->add('motif', TextareaType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input', 'rows' => 3]
            ])
            ->add('moisDeduction', NumberType::class, [
                'label' => 'Mois de déduction',
                'attr' => ['class' => 'form-input', 'min' => 1, 'max' => 12]
            ])
            ->add('anneeDeduction', NumberType::class, [
                'label' => 'Année de déduction',
                'attr' => ['class' => 'form-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AvanceSalaire::class,
        ]);
    }
}
