<?php

namespace App\Form;

use App\Entity\Post;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PostType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        ->add('titre')
        ->add('description')
        ->add('imageFile', VichImageType::class, [
            'label' => 'Image de la recette',
            'required' => false
        ])
       
        ->add('categorie', ChoiceType::class, [
            'choices' => [
                'Nouveauté' => 'nouveaute',
                'Conscience' => 'conscience',
                'Commercial' => 'commercial',
            ],
            'multiple' => false, // Allow only single choice
            'expanded' => false,
            // other options as needed
        ])
        
        ->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
