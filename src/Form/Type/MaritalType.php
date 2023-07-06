<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MaritalType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'choices' => [
                'Marie(e)' => 'Maried',
                'Celibataire' => 'Single',
                'Veuf(ve)' => 'Widow',
                'Divorce' => 'Divorced'
            ],
        ]);
    }

    public function getParent(): string
    {
        return ChoiceType::class;
    }
}
