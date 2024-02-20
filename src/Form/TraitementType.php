<?php

namespace App\Form;

use App\Entity\Traitement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TraitementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('date_taitement')
            
            ->add('responsable')
            ->add('statut', ChoiceType::class, [
                'label' => 'statut',
                'choices' => [
                    'en cours' => 'en cours',
                    'en attente' => 'en attente',
                    'termine' => 'termine',
                    
                ],
                'placeholder' => 'Choisir une option',
                'required' => true,
            ])
            ->add('remarque')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Traitement::class,
        ]);
    }
}
