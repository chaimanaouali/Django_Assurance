<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Import FileType
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Traitement;

class TraitementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_taitement')
            ->add('responsable')
            ->add('statut', ChoiceType::class, [
                'label' => 'statut',
                'choices' => [
                    'en cours' => 'en cours',
                    'en attente' => 'en attente',
                    'termine' => 'termine',
                ],
                'placeholder' => 'Choisir une option',
                'required' => true,
            ])
            ->add('remarque', TextareaType::class)
            ->add('photo', FileType::class, [
                'label' => 'Your Image (JPG, JPEG, PNG file)',
                'mapped' => false, // tells Symfony not to try to map this field to any entity property
                'required' => false, // allow the field to be empty, so you can remove the image
                'attr' => ['accept' => 'image/*']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Traitement::class,
        ]);
    }
}
