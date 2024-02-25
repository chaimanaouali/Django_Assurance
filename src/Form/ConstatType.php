<?php

namespace App\Form;

use App\Entity\Constat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ConstatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('iduser')
            ->add('date')
            ->add('lieu')
            ->add('description')
            ->add('conditionroute', ChoiceType::class, [
                'label' => 'Condition de la route',
                'choices' => [
                    'Route glissante' => 'Route glissante',
                    'Météo' => 'meteo',
                    'traveaux routiers' => 'traveaux routiers',
                    'Brouillard' => 'brouillard',
                    'Faible visibilité' => 'Faible visibilité',
                    'circulation' => 'circulation',
                    'Route montagneuses' => 'Route montagneuses',
                ],
                'placeholder' => 'Choisir une option',
                'required' => true,
            ])
            ->add('rapportepolice', ChoiceType::class, [
                'label' => 'Rapport de Police',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],'expanded' => true, // Renders as radio buttons
                'multiple' => false, // Allows selecting only one option
                'required' => true, // Set to true if you want to force a selection
            ])
            ->add('photo', FileType::class, [
                'label' => 'Your Image (JPG, JPEG, PNG file)',
                'mapped' => false, // tells Symfony not to try to map this field to any entity property
                'required' => false, // allow the field to be empty, so you can remove the image
                'attr' => ['accept' => 'image/*']
            ])
          
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Constat::class,
        ]);
    }
    
}