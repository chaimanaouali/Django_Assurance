<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Security\Core\Security;

class Voiture1Type extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser(); // Get the currently logged-in user

        if ($user && in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            $builder->add('email');

        } else {
            $builder->add('email', TextType::class, [
                'data' => $user->getEmail(), // Set default value to user's email
                'disabled' => true, // Disable editing of the email field
            ]);
        }

        $builder->add('matricule', TextType::class, [
            'constraints' => [
                new Assert\Regex([
                    'pattern' => '/^\d{3}-[a-zA-Z]+-\d{4}$/',
                    'message' => 'Le matricule doit être au format "XXX-TUNIS-XXXX".',
                ]),
            ],
            'attr' => [
                'placeholder' => 'XXX-TUNIS-XXXX',
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
        ->add('puissance');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
