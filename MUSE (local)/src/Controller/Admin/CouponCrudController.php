<?php

namespace App\Controller\Admin;

use App\Entity\Coupon;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class CouponCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Coupon::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id')
                ->hideOnForm();

        yield TextField::new('code');
        
        yield TextField::new('userName');

        yield Field::new('discountRate');
        
        yield Field::new('validated');

        yield Field::new('Cart');
    }
}