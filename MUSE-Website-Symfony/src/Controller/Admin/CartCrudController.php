<?php

namespace App\Controller\Admin;

use App\Entity\Cart;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CartCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Cart::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id')
            ->hideOnForm();

        yield TextField::new('clientOrderId');

        yield AssociationField::new('user')
            ->autocomplete();

        yield Field::new('validated');

        yield DateField::new('orderDate')
            ->hideOnForm();

        yield Field::new('shipped');

        yield DateField::new('shipmentDate')
            ->hideOnForm();

        yield TextField::new('carrier');

        yield TextField::new('carrierShipmentId');

        yield Field::new('additionalDiscountRate')
            ->setHelp('Ex: 0.20 pour 20%');

        yield Field::new('total');

        yield Field::new('invoice');

        yield AssociationField::new('orderDetails')
            ->autocomplete();

        yield AssociationField::new('billingAddress')
                ->autocomplete();

        yield AssociationField::new('deliveryAddress')
                ->autocomplete();

    }
    
}
