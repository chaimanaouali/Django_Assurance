<?php

namespace App\Form;


use App\Entity\Mecanicien;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length; 
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Validator\Constraints;
use App\Validator\Constraints\StartsWithUppercase;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

use App\Entity\ReponseDevis;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Form\YourFormType;

class MecanicienType extends AbstractType
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
