<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Post; // Import the Post entity

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contenu', TextareaType::class, [
                'label' => 'Contenu',
            ])
         // ->add('dateCreation', DateTimeType::class, [
          //   'label' => 'Date de création',
            //])
            ->add('auteur', TextType::class, [
                'label' => 'Auteur',
            ])
           ->add('post', EntityType::class, [
                'class' => Post::class, // Use the Post entity class directly
                'choice_label' => 'titre', // Assuming 'titre' is the property to display in the dropdown
                'label' => 'Post',
                ]);
           
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
        ]);
    }
}
