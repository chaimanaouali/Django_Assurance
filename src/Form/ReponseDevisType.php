<?php

namespace App\Form;

use App\Entity\ReponseDevis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Devis;


class ReponseDevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etat', ChoiceType::class, [
            'choices' => [
                'En attente' => 'en attente',
                'Refusé' => 'refusé',
                'En traitement' => 'en traitement',
                'Validé' => 'validé',
            ],
            'placeholder' => 'Sélectionner un état',
            'required' => true,
                ])
            
            ->add('decision')
            ->add('date_reglement')
            ->add('delai_reparation')
            ->add('duree_validite')
            ->add('documents', FileType::class, [
                'label' => 'Documents',
                'mapped' => false,
                'required' => false,
                       ])
                       ->add('email', EntityType::class, [
                        'class' => Devis::class, // Entité cible
                        'choice_label' => 'email', // Propriété de l'entité à afficher dans la liste déroulante
                        'placeholder' => 'Sélectionnez un email', // Optionnel : message par défaut
                        // Autres options...
                    ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponseDevis::class,
        ]);
    }
}
