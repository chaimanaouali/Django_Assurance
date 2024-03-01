<?php

namespace App\Form;


use App\Entity\Evaluation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class Evaluation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_client', TextType::class, [
                'label' => 'nom_client',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre Nom.']),
                    new Regex([
                        'pattern' => '/^[A-Z]/',
                        'message' => 'Le nom doit commencer par une majuscule.'
                    ]),
                ],
            ])
            ->add('prenom_client', TextType::class, [
                'label' => 'prenom_client',
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez entrer votre Prénom.']),
                    new Regex([
                        'pattern' => '/^[A-Z]/',
                        'message' => 'Le prénom doit commencer par une majuscule.'
                    ]),
                ],
            ])
            ->add('avis_client')
            ->add('date_eval')
            ->add('mecanicien')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluation::class,
        ]);
    }
}
