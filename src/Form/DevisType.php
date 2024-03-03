<?php

namespace App\Form;

use App\Entity\Devis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use VictorPrdh\RecaptchaBundle\Form\ReCaptchaType;


class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('adresse')
            ->add('email')
            ->add('date_naiss', ChoiceType::class, [
                'label' => 'Date of Birth',
                'choices' => $this->buildDateChoices(), ])
            ->add('num_tel')
            ->add('modele')
            ->add('puissance')
            ->add('prix')
            ->add('recaptcha',RecaptchaType::class)
        ;
    }


    private function buildDateChoices()
    {
        $years = range(1950, date('Y')); // Change 1900 to any starting year you want
        $months = range(1, 12);
        $days = range(1, 31);

        $dateChoices = [];

        foreach ($years as $year) {
            foreach ($months as $month) {
                foreach ($days as $day) {
                    // Check if the day is valid for the given month and year
                    if (checkdate($month, $day, $year)) {
                        $date = sprintf('%d-%02d-%02d', $year, $month, $day);
                        $dateChoices[$date] = $date;
                    }
                }
            }
        }

        return array_flip($dateChoices); // Flip the array to have date as both value and label
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Devis::class,
        ]);
    }
}
