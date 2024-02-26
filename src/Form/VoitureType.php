<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('matricule', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d{3}-[a-zA-Z]+-\d{4}$/',
                        'message' => ' matricule faut etre au format "XXX-TUNIS-XXXX".',
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'XXX-TUNIS-XXXX    ',
                ],
            ])
            ->add('marque', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]*$/',
                        'message' => 'La marque ne doit pas contenir de chiffres.',
                    ]),
                ],
            ])
            ->add('prix_voiture')
            ->add('puissance')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
