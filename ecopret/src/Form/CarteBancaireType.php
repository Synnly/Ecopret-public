<?php

namespace App\Form;

use App\Entity\CarteCredit;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\CardScheme;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\Luhn;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CarteBancaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero_carte', TextType::class, [
                'attr' => ['placeholder' => 'xxxx xxxx xxxx xxxx'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Luhn(null, 'Le numéro de carte est invalide (Luhn)'),
                    new AtLeastOneOf([
                        new CardScheme('MASTERCARD'),
                        new CardScheme('VISA')
                    ], null, null, 'Le numéro de carte est invalide (Format non reconnu)', null, false)
                ]
            ])
            ->add('nom_carte', TextType::class, [
                'attr' => ['placeholder' => 'NOM Prenom'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[A-Z][A-Za-z-]{0,18}[a-zA-Z] [A-Z][A-Za-z-]{0,18}[a-zA-Z]$/',
                        'message' => 'Le nom et le prénom doivent contenir entre 1 et 19 lettres chacun et doivent être séparés d un espace'
                    ])
                ]
                ])
            ->add('date_expiration', DateType::class, [
                'html5' => false,
                'required' => false,
                'format' => 'M/y',
                'attr' => ['placeholder' => 'mm/yy'],
                'mapped' => false,
                'constraints' => [
                    new GreaterThanOrEqual('+1 month')
                ]
            ])
            ->add('code_cvv', TextType::class, [
                'attr' => ['placeholder' => 'xxx'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{3}$/',
                        'message' => 'Le CVV doit contenir 3 chiffres.'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CarteCredit::class,
        ]);
    }
}