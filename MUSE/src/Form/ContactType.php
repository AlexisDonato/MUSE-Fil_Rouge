<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'attr' => ['class' => 'FirstNameField'],
                    'constraints' => [
                        new Regex([
                            'pattern' => "/^[A-Z][a-zàéèêëîïôöûüùç.]+([ -][A-Z][a-zàéèêëîïôöûüùç.])*/",
                            'message' => "Nom invalide (numéros non autorisés, n'oubliez pas les majuscules)"
                        ]),
                    ]
                ])

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

            ->add('subject', ChoiceType::class, [
                'choices' => [
                    'Connexion' => 'Connexion',
                    'Catégorie(s)' => 'Catégorie(s)',
                    'Produit(s)' => 'Produit(s)',
                    'Panier(s)' => 'Panier(s)',
                    'Commande(s)' => 'Commande(s)',
                    'Autre' => 'Autre'
                ],
                'mapped' => true,
                'multiple' => true,
                'required' => true,
                ])

            ->add('message', TextareaType::class, [
                'attr' => [
                    'placeholder' => "Merci de préciser les références des produits, commandes ou références de dossier dans le cas d'un suivi",
                    'class' => 'font-italic',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
