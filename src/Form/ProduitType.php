<?php

namespace App\Form;

use App\Entity\Categorie;
use App\Entity\Photos;
use App\Entity\Produit;
use App\Entity\Promotion;
use App\Entity\TVA;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class ProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('libelle')
            ->add('description')
            ->add('prix_ht')
            ->add('Photo',EntityType::class, [
                'class' => PHOTOS::class,
'choice_label' => 'id',
            ])
            ->add('TVA', EntityType::class, [
                'class' => TVA::class,
'choice_label' => 'id',
            ])
            ->add('promotion', EntityType::class, [
                'class' => Promotion::class,
'choice_label' => 'id',
            ])
            ->add('categories', EntityType::class, [
                'class' => Categorie::class,
'choice_label' => 'id',
'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produit::class,
        ]);
    }
}
