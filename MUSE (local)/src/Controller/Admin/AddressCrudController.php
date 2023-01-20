<?php

namespace App\Controller\Admin;

use App\Entity\Address;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class AddressCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Address::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id')
                ->hideOnForm();

        yield TextField::new('name');      
        
        yield Field::new('country'); 

        yield Field::new('zipCode'); 

        yield Field::new('city');

        yield IntegerField::new('pathNumber');

        yield TextField::new('pathType');

        yield Field::new('billingAddress');

        yield Field::new('deliveryAddress');

        yield AssociationField::new('user')
        ->autocomplete();

    }
    
}
