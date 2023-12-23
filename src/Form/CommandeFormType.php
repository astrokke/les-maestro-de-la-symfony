<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Commande;
use App\Entity\Etat;
use App\Entity\Livraison;
use App\Entity\Paiement;
use App\Entity\Panier;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('Livraison', EntityType::class, [
                'class' => Livraison::class,
                'choice_label' => 'libelle',

            ])
            ->add('Paiement', EntityType::class, [
                'class' => Paiement::class,
                'choice_label' => 'libelle',
            ])
            ->add('est_facture', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => 'rue',
            ])

            ->add('est_livre', EntityType::class, [
                'class' => Adresse::class,
                'choice_label' => 'rue',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
