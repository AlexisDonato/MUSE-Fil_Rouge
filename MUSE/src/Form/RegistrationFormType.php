<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'EmailField'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
                        'message' => 'Email invalide'
                    ]),
                ]
            ])

            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'first_options'  => ['label' => 'Saisir le mot de passe'],
                'second_options' => ['label' => 'Saisir à nouveau'],
                'attr' => ['autocomplete' => 'new-password',
                           'class' => 'PasswordField',
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Merci de renseigner votre mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                    new Regex([
                        'pattern' => '/^\S*(?=\S{6,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/',
                        'message' => '6 caractères, 1 majuscule, 1 minuscule et 1 chiffre minimum',
                    ]),
                ]
            ])
           
            ->add('userName', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'FirstNameField'],
                    'constraints' => [
                        new Regex([
                            'pattern' => "/^[A-Z][a-zàéèêëîïôöûüùç.]+([ -][A-Z][a-zàéèêëîïôöûüùç.])*/",
                            'message' => "Prénom invalide (numéros non autorisés, n'oubliez pas les majuscules)"
                        ]),
                    ]
                ])

            ->add('userLastName', TextType::class, [
            'required' => true,
            'attr' => ['class' => 'LastNameField'],
                'constraints' => [
                    new Regex([
                        'pattern' => "/^[A-Z][a-zàéèêëîïôöûüùç.]+([ -][A-Z][a-zàéèêëîïôöûüùç.])*/",
                        'message' => "Nom invalide (numéros non autorisés, n'oubliez pas les majuscules)"
                    ]),
                ]
            ])

            ->add('birthdate', DateType::class, [
                'widget' => 'single_text',
                // this is actually the default format for single_text
                'format' => 'yyyy-MM-dd',
                'required' => true,
            ])

            ->add('phoneNumber', TextType::class, [
                'required' => true,
                'attr' => [
                    'class' => 'PhoneField',
                    ],  
                'constraints' => [
                    new Regex([
                        'pattern' => "/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/", // French phone number
                        'message' => 'Numéro de téléphone invalide'
                    ]),
                ]
            ])

            ->add('pro', CheckboxType::class, [
                'mapped' => false,
                'required' => false,
                'row_attr' => [
                    'onclick' => 'proSubForm()',
                ],
                ])

            ->add('proCompanyName', TextType::class, [
                'required' => false,
                ])

            ->add('proDuns', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]{9}$/',
                        'message' => 'Numéro invalide : entrée à 9 chiffres (ex: "123456789")'
                    ]),
                ]
            ])

            ->add('proJobPosition', TextType::class, [
                'required' => false,
                ])

            ->add('address_name', TextType::class, [
                // 'help' => 'ex: Domicile, Adresse de livraison n°1, Adresse de facturation n°3...'

            ])

            ->add('address_country', TextType::class, [

                ])

            ->add('address_zipcode', TextType::class, [

                ])

            ->add('address_city', TextType::class, [

                ])

            ->add('address_path_type', TextType::class, [
                // 'help' => 'ex: Rue, Avenue, Impasse...'
                ])

            ->add('address_path_number', TextType::class, [

                ])
    
            ->add('agreeTerms', CheckboxType::class, [
                'required' => true,
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => "Veuillez accepter nos conditions d'utilisation",
                    ]),
                ],
            ])
        ;

    }




    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Commenting the following unbinds to the class, and allows to set several classes in the controller
            // 'data_class' => User::class,
        ]);
    }
}
