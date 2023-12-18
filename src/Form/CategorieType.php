<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Photos;
use App\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class CategorieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('Photo',EntityType::class, [
                'class' => PHOTOS::class,
'choice_label' => 'id',
            ])
            ->add('categorie_enfant', EntityType::class, [
                'class' => Categorie::class,
'choice_label' => 'id',
            ])
            ->add('Produit', EntityType::class, [
                'class' => Produit::class,
'choice_label' => 'id',
'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categorie::class,
        ]);
    }
}
