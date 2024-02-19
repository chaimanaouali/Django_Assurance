<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Import TextType
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class Post1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('keyword', TextType::class, [
                'label' => 'Search Keyword',
                'required' => false,
            ])
            ->add('titre')
            ->add('description')
            ->add('image', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'data_class' => null, // Set data_class to null to avoid binding to entity property
                // Add more options as needed, such as validation constraints
            ])
            ->add('dateCreation', DateTimeType::class)
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Category 1' => 'category1',
                    'Category 2' => 'category2',
                    'Category 3' => 'category3',
                ],
                'multiple' => false, // Allow only single choice
                'expanded' => false,
                // other options as needed
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Active' => 'active',
                    'Inactive' => 'inactive',
                ],
                'multiple' => false, // Allow only single choice
                'expanded' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
