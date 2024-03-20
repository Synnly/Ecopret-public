<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class AjouterAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class)
            ->add('description', TextareaType::class,[ 'attr' => [
                'rows' => 3,
                'cols' => 32, 
            ],    
        ])
            ->add('ajouterPhoto', FileType::class, [
                'attr' => ['onchange' => "ajoutPhoto()",],
                'required' => false,
            ])
            ->add('ajouterPhoto2', FileType::class, [
                'attr' => ['onchange' => "ajoutPhoto()",],
                'required' => false,
            ])
            ->add('ajouterPhoto3', FileType::class, [
                'attr' => ['onchange' => "ajoutPhoto()",],
                'required' => false,
            ])
            ->add('prix', TextType::class, ['constraints' => [
                new Regex([
                    'pattern' => '/^[0-9]+$/',
                    'message' => 'Doit,contenir au moins un chiffre'
                ])]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
