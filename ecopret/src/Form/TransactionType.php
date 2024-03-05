<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $options['data'];

        $builder
            ->add('id_transaction', TextType::class, [
                'label' => "N° de transaction",
                'data' => $data['id_transaction'],
                'attr' => [
                    'readonly' => true,
                    'class' => 'id_transaction'
                ]
            ])
            ->add('nom_annonce', TextType::class, [
                'label' => "Annonce",
                'data' => $data['nom_annonce'],
                'attr' => [
                    'readonly' => true,
                    'class' => 'nom_annonce'
                ]
            ])
            ->add('statut', TextType::class, [
                'data' => $data['statut'],
                'attr' => [
                    'class' => 'statut',
                    'readonly' => true,
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
