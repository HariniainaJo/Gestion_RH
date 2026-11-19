<?php

namespace App\Form;

use App\Entity\AxeNavette;
use App\Entity\Navette;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NavetteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('codeNavette', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: NAV-001']
            ])
            ->add('capacite', IntegerType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'ex: 15']
            ])
            ->add('axe', EntityType::class, [
                'class' => AxeNavette::class,
                'choice_label' => 'nomAxe',
                'attr' => ['class' => 'form-input']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Navette::class,
        ]);
    }
}
