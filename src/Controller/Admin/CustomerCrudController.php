<?php
// src/Controller/Admin/CustomerCrudController.php

namespace App\Controller\Admin;

use App\Entity\Customer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CustomerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Customer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Client')
            ->setEntityLabelInPlural('Clients')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle('index', '👥 Liste des clients');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        
        yield TextField::new('firstName', 'Prénom')
            ->setRequired(true);
        
        yield TextField::new('lastName', 'Nom')
            ->setRequired(true);
        
        yield EmailField::new('email', 'Email')
            ->setRequired(true);
        
        yield TelephoneField::new('phone', 'Téléphone')
            ->setRequired(true);
        
        yield DateTimeField::new('createdAt', 'Date d\'inscription')
            ->hideOnForm();
    }
}