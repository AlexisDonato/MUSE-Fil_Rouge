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
            ->add('code')
            ->add('discountRate', null, [
                'help' => 'Ex: "0.20" pour 20%'
            ])
            ->add('validated')
            ->add('cart', EntityType::class, [
                'class' => Cart::class,
                'query_builder' => function (CartRepository $cartRepository) {
                    return $cartRepository->createQueryBuilder('c')
                        ->join("c.user", "u")
                        ->where('c.validated = 0')
                        ->orderBy('u.email', 'ASC');
                },
                'choice_label' => 'user',
                'multiple' => false,
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
