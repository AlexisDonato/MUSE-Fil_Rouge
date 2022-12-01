<?php

namespace App\Form;

use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class SelectAddressType extends AbstractType
{
    private $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('selectBillingAddress', EntityType::class, [
                'mapped' => false,
                'required' => true,
                'multiple' => false,
                'class' => Address::class,
                'query_builder' => function (AddressRepository $addressRepository) {
                    return $addressRepository->createQueryBuilder('a')
                        ->join("a.user", "u")
                        ->where('u.id=' . $this->security->getUser()->getId());
                },
                'choice_label' => 'fullName',
                
            ])
            ->add('selectDeliveryAddress', EntityType::class, [
                'mapped' => false,
                'required' => true,
                'multiple' => false,
                'class' => Address::class,
                'query_builder' => function (AddressRepository $addressRepository) {
                    return $addressRepository->createQueryBuilder('a')
                        ->join("a.user", "u")
                        ->where('u.id=' . $this->security->getUser()->getId());
                },
                'choice_label' => 'fullName',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Address::class,
        ]);
    }
}
