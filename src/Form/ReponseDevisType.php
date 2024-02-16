<?php

namespace App\Form;

use App\Entity\ReponseDevis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseDevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('etat')
            ->add('decision')
            ->add('dateReglement')
            ->add('delaiReparation')
            ->add('dureeValidite')
            ->add('documents')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReponseDevis::class,
        ]);
    }
}
