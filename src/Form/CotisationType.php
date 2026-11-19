<?php

namespace App\Form;

use App\Entity\Cotisation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CotisationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Code',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Ex: CNAPS, OSTIE, IRSA',
                ],
            ])
            ->add('nom', TextType::class, [
                'label' => 'Libellé',
                'attr' => [
                    'class' => 'form-input',
                    'placeholder' => 'Nom de la cotisation',
                ],
            ])
            ->add('taux', NumberType::class, [
                'label' => 'Taux (ex: 0.01 pour 1%)',
                'scale' => 4,
                'attr' => [
                    'class' => 'form-input',
                    'step' => 0.0001,
                ],
            ])
            ->add('plafond', NumberType::class, [
                'label' => 'Plafond mensuel (optionnel)',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'class' => 'form-input',
                    'step' => 0.01,
                ],
            ])
            ->add('seuil', NumberType::class, [
                'label' => 'Seuil (optionnel, ex: IRSA)',
                'required' => false,
                'scale' => 2,
                'attr' => [
                    'class' => 'form-input',
                    'step' => 0.01,
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cotisation::class,
        ]);
    }
}

