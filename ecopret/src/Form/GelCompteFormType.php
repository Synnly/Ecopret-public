<?php

namespace App\Form;

use App\Entity\Compte;
use App\Entity\Utilisateur;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class GelCompteFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('deb',DateType::class,[
                'label' => "Date de début",
                'required' => true,
                'mapped' => false,
                'attr' => ['class' => 'gel_date'],
                'constraints' => [new GreaterThanOrEqual('today UTC')]
            ])
            ->add('fin',DateType::class,[
                'label' => "Date de fin",
                'required' => true,
                'mapped'=>false,
                'attr' => ['class' => 'gel_date'],
                'constraints' => [new GreaterThanOrEqual('today UTC')]
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Enregistrer >',
                'attr' => ['class' => 'gel_btn'],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
