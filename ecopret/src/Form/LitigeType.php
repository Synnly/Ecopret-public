<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LitigeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $options['data'];

        $builder
        ->add('id_litige', TextType::class, [
            'label' => "N° de litige",
            'data' => $data['id_litige'],
            'attr' => [
                'readonly' => true,
                'class' => 'id_litige'
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
        ->add('nom_accuse', TextType::class, [
            'label' => "Accusé",
            'data' => $data['nom_accuse'],
            'attr' => [
                'readonly' => true,
                'class' => 'nom_accuse'
            ]
        ])
        ->add('description', TextareaType::class, [
            'data' => $data['description'],
            'attr' => [
                'class' => 'description',
                'readonly' => true,
                'rows' => max(1, round(strlen($data['description'])/50)),
                'cols' => 50
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
            'auto_initialize' => false
        ]);
    }
}
