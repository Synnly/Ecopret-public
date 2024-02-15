<?php

namespace App\Form;

use App\Entity\CarteCredit;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\Luhn;
use Symfony\Component\Validator\Constraints\AtLeastOneOf;
use Symfony\Component\Validator\Constraints\CardScheme;




class CreditCardFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numero_carte', NumberType::class, [
                'constraints' => [
                    //Le champs ne doit pas être vide sinon envoie du message
                    new NotBlank(['message' => 'Les numéros de cartes ne doivent pas être vides.']),
                    new AtLeastOneOf([
                        new CardScheme('MASTERCARD'),
                        new CardScheme('VISA')
                    ], null, null, 'Le numéro de carte est invalide', null, false)
                ],
            ])
            // Même principe avec les autres champs
            ->add('nom_carte', TextType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le nom est requis.']),
                    new Regex([
                        'pattern' => '/^[a-zA-Z- ][a-zA-Z]+(?:[- ][a-zA-Z- ][a-zA-Z]+)*$/',
                        'message' => 'Le nom n\'est pas valide.'
                    ])
                ],
            ])
            ->add('code_cvv', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Le CVV est requis.']),
                    new Regex([
                        'pattern' => '/^\d{3,4}$/',
                        'message' => 'Le CVV n\'est pas valide.'
                    ])
                ],
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
