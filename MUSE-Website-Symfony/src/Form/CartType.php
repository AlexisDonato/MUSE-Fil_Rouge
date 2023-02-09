<?php

namespace App\Form;

use App\Entity\Cart;
use App\Entity\Address;
use App\Repository\AddressRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
// use Symfony\Component\Security\Core\Security;

class CartType extends AbstractType
{
    // private $security;

    // public function __construct(Security $security) {
    //     $this->security = $security;
    // }


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('clientOrderId')
            ->add('validated')
            ->add('orderDate')
            ->add('shipped')
            ->add('shipmentDate')
            ->add('carrier')
            ->add('carrierShipmentId')
            ->add('total')
            ->add('additionalDiscountRate')
            ->add('user')
            ->add('billingAddress', null, [
                'choice_label' => 'fullName',
                ])
            ->add('deliveryAddress', null, [
                'choice_label' => 'fullName',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cart::class,
        ]);
    }
}
