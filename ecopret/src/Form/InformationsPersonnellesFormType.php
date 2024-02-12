<?php

namespace App\Form;

use App\Entity\CarteCredit;
use App\Entity\Compte;
use App\Entity\Lieu;
use App\Entity\Note;
use App\Entity\Notification;
use App\Entity\Transaction;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationsPersonnellesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('NomCompte')
            ->add('PrenomCompte')
            ->add('motDePasseCompte')
            ->add('AdresseMailCOmpte')
            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Compte::class,
        ]);
    }
}
