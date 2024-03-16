<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class ChoisirAnnonceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oui', SubmitType::class, [
                'label' => 'Choisir',
                'attr' => ['class' => 'choisir_annonce_btn']
            ])
            ->add('non', SubmitType::class, [
                'label' => 'Annuler',
                'attr' => ['class' => 'choisir_annonce_btn']
            ])
            ->add('numero_choix', TextType::class, [
		'required' => false,
                'attr' => [
                    'placeholder' => 'Choisissez un numéro']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
