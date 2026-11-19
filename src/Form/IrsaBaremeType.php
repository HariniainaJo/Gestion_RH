<?php

namespace App\Form;

use App\Entity\IrsaBareme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IrsaBaremeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('salaireMin', NumberType::class, [
                'label' => 'Salaire minimum',
                'scale' => 2,
                'attr' => [
                    'class' => 'form-input',
                    'step' => 0.01,
                ],
            ])
            ->add('salaireMax', NumberType::class, [
                'label' => 'Salaire maximum (laisser vide pour illimité)',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'class' => 'form-input',
                    'step' => 0.01,
                ],
            ])
            ->add('taux', NumberType::class, [
                'label' => 'Taux pour cette tranche (ex: 0.20 pour 20%)',
                'scale' => 4,
                'attr' => [
                    'class' => 'form-input',
                    'step' => 0.0001,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => IrsaBareme::class,
        ]);
    }
}

