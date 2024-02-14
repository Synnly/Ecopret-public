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
                'constraints' => [
                    new NotBlank(['message' => 'Le numéro de carte est requis.']),
                    new Luhn(null, 'Le numéro de carte est invalide'),
                    new AtLeastOneOf([
                        new CardScheme('MASTERCARD'),
                        new CardScheme('VISA')
                    ], null, null, 'Le numéro de carte est invalide', null, false)
                ]
            ])
            ->add('date_expiration', DateType::class, [
                'empty_data' => '',
                'constraints' => [
                    new GreaterThanOrEqual('+1 month')
                ]
            ])
            ->add('code_cvv', IntegerType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le CVV est requis.']),
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
