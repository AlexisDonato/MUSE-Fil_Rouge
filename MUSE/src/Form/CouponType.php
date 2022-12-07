<?php

namespace App\Form;

use App\Entity\Cart;
use App\Entity\Coupon;
use App\Repository\CartRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CouponType extends AbstractType

{
    // private CartRepository $cartRepository;

    // public function __construct(Cart $cart) {
    //     $cart =$this->$cart;
    // }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', null, [
                'help' => 'Pour plus de sécurité, mélangez les caractères. Ex: "Mµ$€20°/o" pour 20%...'
            ])
            ->add('discountRate', null, [
                'help' => 'Ex: "0.20" pour 20%'
            ])
            ->add('validated', null, [
                'help' => 'Pour activer le bon'
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => Coupon::class,
        ]);
    }
}
