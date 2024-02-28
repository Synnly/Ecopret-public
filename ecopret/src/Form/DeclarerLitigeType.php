<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class DeclarerLitigeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('typeUtil', ChoiceType::class, [
                'choices' => [
                    'Client' => 'client',
                    'Prestataire' => 'prest',
                ],
                'label' => "Je suis:",
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['placeholder' => 'Prénom'],
                'required' => false,
                'mapped' => false,
                'label' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Z][A-Za-z-]{0,18}[a-zA-Z]$/',
                        'message' => 'Le prénom doit contenir entre 1 et 19 lettres'
                    ])
                ]
            ])
            ->add('mail', TextType::class, [
                'attr' => ['placeholder' => 'Email'],
                'label' => false,
                'constraints' => [
                    //Le champs ne doit pas être vide sinon envoie du message
                    new NotBlank(['message' => 'L\'adresse mail est requise.']),

                    //Regex pour tester l'adresse mail
                    new Regex([
                        'pattern' => '/^[a-zA-Z]([a-zA-Z0-9-]*\.)?[a-zA-Z0-9-]+@[a-zA-Z-]+\.[a-zA-Z]{2,}$/',
                        'message' => 'Votre adresse mail n\' est pas valide.'
                    ])
                ],
            ])
            ->add('numTransaction',NumberType::class,[
                'attr' => ['placeholder' => 'Numéro de transaction'],
                'label' => false,
                'required' => true,
                'mapped' => false,
            ])
            ->add('description',TextareaType::class,[
                'attr' => ['placeholder' => 'Description du problème'],
                'label' => false,
                'required' => true,
                'mapped' => false,
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'decl_btn'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
