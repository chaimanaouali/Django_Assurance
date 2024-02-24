<?php

namespace App\Form;

use App\Entity\Contrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Type; // Import Type entity at the top of the file

class Contrat1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebutContrat')
            ->add('datefinContrat')
            ->add('type_couverture', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'description', // Property of Type entity to display
                'placeholder' => 'Choose a type de couverture',
                'required' => true,
            ])
                        ->add('adresseAssur', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^\d{2}-rue-[a-zA-Z]+-[a-zA-Z]/',
                        'message' => 'Le adress doit être au format "XX-rue-XXXX-nom de ville".',
                    ]),
                ],
                'attr' => [
                    'placeholder' => '12-rue-NomRue-NomVille',
                ],
            ])
            ->add('numeroAssur', TextType::class, [
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[259]\d{7}$/',
                        'message' => 'Le numéro doit avoir une longueur de 8 chiffres.',
                    ]),
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
