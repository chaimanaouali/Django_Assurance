<?php

namespace App\Form;

use App\Entity\Type;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TypeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('typeCouverture', ChoiceType::class, [
                'choices' => [
                    'Responsabilité civile' => 'responsabilite_civile',
                    'Collision' => 'collision',
                    'Comprehensive (tous risques)' => 'comprehensive',
                    'Blessures corporelles' => 'blessures_corporelles',
                    'Protection juridique' => 'protection_juridique',
                    'Assistance routière' => 'assistance_routiere',
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Type::class,
        ]);
    }
}
