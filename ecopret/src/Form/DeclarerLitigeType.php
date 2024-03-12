<?php

namespace App\Form;

use App\Entity\Litige;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class DeclarerLitigeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('transaction',IntegerType::class,[
                'attr' => ['placeholder' => 'Numéro de transaction'],
                'label' => false,
                'required' => true,
                'mapped' => false,
            ])
            ->add('description',TextareaType::class,[
                'attr' => ['placeholder' => 'Description du problème'],
                'label' => false,
                'required' => true,
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Valider',
                'attr' => ['class' => 'decl_btn'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Litige::class
        ]);
    }
}
