<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\Promotion;
use App\Entity\TVA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class AdminProduitFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('prix_ht')
            ->add('TVA', EntityType::class, [
                'class' => TVA::class,
                'choice_label' => 'taux_tva',
            ])
            ->add('promotion', EntityType::class, [
                'class' => Promotion::class,
                'choice_label' => 'libelle',
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'libelle',
                'multiple' => true,
            ])->add('upload_file', FileType::class, [
                'label' => false,
                'mapped' => false, // Tell that there is no Entity to link
                'required' => true,
                'constraints' => [
                    new File([
                        'mimeTypes' => [
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
            'data_class' => Produit::class,
        ]);
    }
}
