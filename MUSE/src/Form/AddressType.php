<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('country')

            ->add('zipcode', TextType::class, [
                'attr' => ['maxlength' => 5],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{5}$/',
                        'message' => 'Code postal invalide : entrée à 5 chiffres (ex: "75000")'
                        ]),
                    ]
                ])

            ->add('city', TextType::class, [
                ])

            ->add('pathType')
            ->add('pathNumber')

            ->add('billingAddress', CheckboxType::class, [
                'required' => false,
            ])
            
            ->add('deliveryAddress', CheckboxType::class, [
                'required' => false,
            ])

            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Address::class,
        ]);
    }
}
