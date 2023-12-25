<?php

namespace App\Form;

use App\Entity\Adresse;
use App\Entity\Commande;
use App\Entity\Etat;
use App\Entity\Livraison;
use App\Entity\Paiement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminCommandeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

      
            ->add('Etat', EntityType::class, [
                'class' => Etat::class,
                'choice_label' => 'libelle',
            ]);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
