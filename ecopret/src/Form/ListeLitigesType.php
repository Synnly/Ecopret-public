<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListeLitigesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $data = $options['data']['data'];   // ????????????????????

        // Ajoute dynamiquement autant de formulaires que de litiges
        for($i = 0; $i < $data['nbForms']; $i++){
            $builder->add("litige$i", LitigeType::class, [
                'label' => false,
                'data' => $data[$i],
                'attr' => [
                    'class' => 'litige'
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
