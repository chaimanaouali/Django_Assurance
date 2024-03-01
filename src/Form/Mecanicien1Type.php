<?php

namespace App\Form;

use App\Entity\Mecanicien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Mecanicien1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre Nom.']),
                    new Regex([
                        'pattern' => '/^[A-Z]/',
                        'message' => 'Le nom doit commencer par une majuscule.'
                    ]),
                ],
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre prénom.']),
                    new Regex([
                        'pattern' => '/^[A-Z]/',
                        'message' => 'Le prénom doit commencer par une majuscule.'
                    ]),
                ],
            ])

            ->add('localisation')
            ->add('disponibilite')
            ->add('numero', null, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Type(['type' => 'numeric']),
                    new Length(['max' => 8]),
                ],
            ])

            ->add('image',FileType::class, [
                'label' => 'image',
                'mapped' => false,
                'required' => false,
            ])

            ->add('email', null, [
                'constraints' => [
                    new Assert\NotBlank(message:"Mail please"),
                    new Assert\Email(message: 'The email {{ value }} is not a valid email.'),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mecanicien::class,
        ]);
    }
}
