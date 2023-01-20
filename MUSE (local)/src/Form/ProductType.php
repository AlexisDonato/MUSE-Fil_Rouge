<?php

namespace App\Form;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\SupplierRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('price')
            ->add('description')
            ->add('content')

            ->add('image', FileType::class, [
                'mapped' => true,
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'form-control-file'
                ],
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Type de fichier invalide (uniquement jpeg, jpg ou png)',
                    ])
                ],
                'data_class' => null
            ])

            ->add('image1', FileType::class, [
                'mapped' => true,
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'form-control-file'
                ],
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Type de fichier invalide (uniquement jpeg, jpg ou png)',
                    ])
                ],
                'data_class' => null
            ])

            ->add('image2', FileType::class, [
                'mapped' => true,
                'required' => false,
                'attr' => [
                    'accept' => 'image/*',
                    'class' => 'form-control-file'
                ],
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Type de fichier invalide (uniquement jpeg, jpg ou png)',
                    ])
                ],
                'data_class' => null
            ])

            ->add('quantity')

            ->add('discount')

            ->add('discountRate', TextType::class, [
                'help' => 'ex: entrez 0.20 pour 20%',
            ])

            ->add('supplier', null, [
                'query_builder' => function (SupplierRepository $supplierRepository) {
                    return $supplierRepository->createQueryBuilder('s')
                    ->orderBy('s.name', 'ASC');
                }
            ]
            )

            ->add('category', null, [
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                    ->orderBy('c.name', 'ASC');
                }
            ]
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
