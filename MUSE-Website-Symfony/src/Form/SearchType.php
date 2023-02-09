<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Category;
use App\Entity\Supplier;
use App\Repository\CategoryRepository;
use App\Repository\SupplierRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class SearchType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Mot-clé'
                ]
            ])

            ->add('category', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $categoryRepository) {
                    return $categoryRepository->createQueryBuilder('c')
                        ->join("c.product", "p")
                        ->where('SIZE(c.product) != 0')
                        ->orderBy('c.name', 'ASC');
                },
                'choice_label' => 'name',
                'expanded' => false,
                'mapped' => true,
                'multiple' => true,
            ])

            ->add('supplier', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Supplier::class,
                'query_builder' => function (SupplierRepository $supplierRepository) {
                    return $supplierRepository->createQueryBuilder('s')
                    ->orderBy('s.name', 'ASC');
                },
                'mapped' => true,
                'multiple' => true,
                'expanded' => false,
                'choice_label' => 'name'
            ])

            ->add('min', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix min'
                ]
            ])

            ->add('max', NumberType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Prix max'
                ]
            ])

            ->add('discount', CheckboxType::class, [
                'label' => 'En promotion',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'POST',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
