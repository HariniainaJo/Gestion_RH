<?php

namespace App\Form;

use App\Entity\Employe;
use App\Entity\Poste;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'Nom de famille']
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'Prénom']
            ])
            ->add('dateNaissance', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-input']
            ])
            ->add('adresse', TextareaType::class, [
                'attr' => ['class' => 'form-input', 'rows' => 2]
            ])
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'form-input', 'placeholder' => 'email@entreprise.com']
            ])
            ->add('contact', TextType::class, [
                'attr' => ['class' => 'form-input']
            ])
            ->add('situationMatrimoniale', ChoiceType::class, [
                'choices' => [
                    'Célibataire' => 'Célibataire',
                    'Marié(e)' => 'Marié(e)',
                    'Divorcé(e)' => 'Divorcé(e)',
                    'Veuf(ve)' => 'Veuf(ve)',
                ],
                'attr' => ['class' => 'form-input']
            ])
            ->add('dateEmbauche', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'attr' => ['class' => 'form-input']
            ])
            ->add('statut', ChoiceType::class, [
                'choices' => [
                    'Actif' => 'Actif',
                    'Inactif' => 'Inactif',
                    'En congé' => 'En congé',
                    'Licencié' => 'Licencié',
                ],
                'attr' => ['class' => 'form-input']
            ])
            ->add('poste', EntityType::class, [
                'class' => Poste::class,
                'choice_label' => 'libelle',
                'attr' => ['class' => 'form-input']
            ])
            ->add('photoProfilFile', \Symfony\Component\Form\Extension\Core\Type\FileType::class, [
                'label' => 'Photo de profil (Image)',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('photoCinFile', \Symfony\Component\Form\Extension\Core\Type\FileType::class, [
                'label' => 'Photo CIN (Image)',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
            ->add('cvFile', \Symfony\Component\Form\Extension\Core\Type\FileType::class, [
                'label' => 'CV (PDF)',
                'mapped' => false,
                'required' => false,
                'attr' => ['class' => 'form-input']
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
