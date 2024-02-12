<?php

namespace App\Form;

use App\Entity\Compte;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        //Ajout d'un champs AdresseMailCOmpte de type Texte
        $builder
            ->add('AdresseMailCOmpte', TextType::class, [
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
            //Même principe avec les autres champs
            ->add('NomCompte', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis.']),
                    new Regex([
                        'pattern' => '/^[A-Z][A-Z-]{0,18}[A-Z]$/',
                        'message' => 'Votre nom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres majuscules ou - .'
                    ])
                ],
            ])
            ->add('PrenomCompte', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le prénom est requis.']),
                    new Regex([
                        'pattern' => '/^[A-Z][A-Za-z-]{0,18}[a-zA-Z]$/',
                        'message' => 'Votre prénom doit commencer par une lettre majuscule puis contenir entre 1 et 19 lettres ou - .'
                    ])
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions d\'utilisations.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est requis.']),
                    new Regex([
                        'pattern' => '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$/',
                        'message' => 'Le mot de passe doit contenir au moins 8 caractères dont une majuscule, une minuscule et un chiffre.'
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
