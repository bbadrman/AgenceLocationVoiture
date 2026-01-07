<?php
// src/Controller/Admin/CityCrudController.php

namespace App\Controller\Admin;

use App\Entity\City;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return City::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Ville')
            ->setEntityLabelInPlural('Villes')
            ->setDefaultSort(['name' => 'ASC'])
            ->setPageTitle('index', '🏙️ Liste des villes');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        
        yield TextField::new('name', 'Nom')
            ->setRequired(true);
        
        yield TextField::new('code', 'Code')
            ->setRequired(true)
            ->setHelp('Code court de la ville (ex: RBT, CSA, TNG)');
        
        yield BooleanField::new('isActive', 'Active');
    }
}