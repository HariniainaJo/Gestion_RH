<?php

namespace App\Form;

use App\Entity\Candidat;
use App\Entity\Recrutement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CandidatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-input']
            ])
            ->add('prenom', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('adresse', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('contact', TextType::class, [
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('situationMatrimoniale', ChoiceType::class, [
                'choices' => [
                    'Célibataire' => 'Célibataire',
                    'Marié' => 'Marié',
                    'Divorcé' => 'Divorcé',
                    'Veuf' => 'Veuf',
                ],
                'required' => false,
                'placeholder' => 'Choisir une situation...',
                'attr' => ['class' => 'form-input']
            ])
            ->add('cvFile', FileType::class, [
                'label' => 'CV (PDF or Image)',
                'mapped' => false,
                'required' => true,
                'attr' => ['class' => 'form-input'],
                'constraints' => [
                    new File(
                        maxSize: '5M',
                    )
                ],


            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Candidat::class,
        ]);
    }
}
