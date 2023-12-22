<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Photos;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File as ConstraintsFile;

class AdminCategorieFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('categorie_parente', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'libelle',
                'required' => false,
                'placeholder' => '',
                'empty_data' => null,
            ])
            ->add('upload_file', FileType::class, [
                'label' => false,
                'mapped' => false, // Tell that there is no Entity to link
                'required' => true,
                'constraints' => [
                    new ConstraintsFile([
                        'mimeTypes' => [ // We want to let upload only txt, csv or Excel files
                            'img/jpg',
                            'img/png',
                            'img/jpeg',

                        ],
                        'mimeTypesMessage' => "This document isn't valid.",
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
