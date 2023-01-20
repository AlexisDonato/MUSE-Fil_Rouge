<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Address;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield IntegerField::new('id')
                ->hideOnForm();

        yield EmailField::new('email');
        
        yield TextField::new('userName');

        yield TextField::new('userLastName');
        
        yield DateField::new('birthDate');

        yield TextField::new('phoneNumber');

        yield Field::new('pro');

        yield Field::new('proCompanyName');

        yield Field::new('proJobPosition');

        yield Field::new('proDuns');
        
        $roles = ['ROLE__ADMIN', 'ROLE_SALES', 'ROLE_SHIP', 'ROLE_PRO', 'ROLE_USER'];
        yield ChoiceField::new('roles')
            ->setChoices(array_combine($roles, $roles))
            ->allowMultipleChoices()
            ->renderExpanded()
            ->setHelp('ROLE__ADMIN, ROLE_SALES, ROLE_SHIP, ROLE_PRO, ROLE_USER');

        yield Field::new('vat');

        yield AssociationField::new('address')
                ->autocomplete();

        yield Field::new('isVerified');

        yield AssociationField::new('carts')
        ->autocomplete();

        yield DateField::new('registerDate')
                ->hideOnForm();
    }
    
}
