<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListeTransactionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $options['data']['data'];   // ????????????????????

        // Ajoute dynamiquement autant de formulaires que de transactions
        for($i = 0; $i < $data['nbForms']; $i++){
            $builder->add("transaction$i", TransactionType::class, [
                'label' => false,
                'data' => $data[$i],
                'attr' => [
                    'class' => 'transaction'
                ]
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
