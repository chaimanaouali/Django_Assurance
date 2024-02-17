<?php

namespace App\Form;

use App\Entity\ReponseDevis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

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
            ->add('dateReglement')
            ->add('delaiReparation')
            ->add('dureeValidite')
            ->add('documents', FileType::class, [
                'label' => 'Documents',
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponseDevis::class,
        ]);
    }
}
