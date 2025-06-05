<?php

namespace App\Form;

use App\Entity\Job;
use App\Entity\Company;
use App\Entity\JobCategory;
use App\Entity\JobType as JobTypeEntity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre du poste',
                'attr' => [
                    'class' => 'block w-full rounded border-gray-300',
                    'placeholder' => 'Ex: Développeur Web',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'rows' => 6,
                    'class' => 'block w-full rounded border-gray-300',
                    'placeholder' => 'Décrivez le poste...',
                ],
            ])
            ->add('company', EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'name',
                'label' => 'Entreprise',
                'attr' => ['class' => 'block w-full rounded border-gray-300'],
            ])
            ->add('categories', EntityType::class, [
                'class' => JobCategory::class,
                'choice_label' => 'name',
                'label' => 'Catégories',
                'multiple' => true,
                'expanded' => false,
                'attr' => ['class' => 'block w-full rounded border-gray-300'],
            ])
            ->add('type', EntityType::class, [
                'class' => JobTypeEntity::class,
                'choice_label' => 'name',
                'label' => 'Type de contrat',
                'attr' => ['class' => 'block w-full rounded border-gray-300'],
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'attr' => [
                    'class' => 'block w-full rounded border-gray-300',
                    'placeholder' => 'Ex: France',
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'attr' => [
                    'class' => 'block w-full rounded border-gray-300',
                    'placeholder' => 'Ex: Paris',
                ],
            ])
            ->add('salaryRange', TextType::class, [
                'label' => 'Fourchette salariale',
                'attr' => [
                    'class' => 'block w-full rounded border-gray-300',
                    'placeholder' => 'Ex: 40k€ - 50k€',
                ],
            ])
            ->add('remoteAllowed', CheckboxType::class, [
                'label' => 'Télétravail possible',
                'required' => false,
                'attr' => ['class' => 'ml-2'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'mt-4 bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Job::class,
        ]);
    }
} 