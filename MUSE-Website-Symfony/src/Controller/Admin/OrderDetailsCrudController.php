<?php

namespace App\Controller\Admin;

use App\Entity\OrderDetails;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderDetailsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return OrderDetails::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id')
            ->hideOnForm();

        yield AssociationField::new('product')
            ->autocomplete();

        yield IntegerField::new('quantity');

        yield AssociationField::new('cart')
            ->autocomplete();

        yield Field::new('subTotal');

    }
    
}
