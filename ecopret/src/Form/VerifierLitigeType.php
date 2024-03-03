<?php

namespace App\Form;

use App\Entity\Litige;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VerifierLitigeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('refuser', SubmitType::class, [
            'attr' => ['value' => 'refuse']
        ])
        ->add('accepter', SubmitType::class, [
            'attr' => ['value' => 'accepte']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
