<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id')
        ->hideOnForm();

        yield TextField::new('name');

        yield IntegerField::new('price');

        yield TextareaField::new('description');

        yield TextField::new('content');

        yield Field::new('discount');

        yield Field::new('discountRate')
            ->setHelp('Ex: 0.20 pour 20%');

        yield IntegerField::new('quantity')
            ->setHelp('Quantité en stock');

        yield Field::new('image');

        yield Field::new('image1');

        yield Field::new('image2');

        yield AssociationField::new('supplier')
        ->autocomplete();

        yield AssociationField::new('category')
        ->autocomplete();
    }
    
}
