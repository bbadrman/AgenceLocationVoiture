<?php
// src/Controller/Admin/PickupPointCrudController.php

namespace App\Controller\Admin;

use App\Entity\PickupPoint;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PickupPointCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return PickupPoint::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Point de collecte')
            ->setEntityLabelInPlural('Points de collecte')
            ->setDefaultSort(['name' => 'ASC'])
            ->setPageTitle('index', '📍 Liste des points de collecte');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        
        yield TextField::new('name', 'Nom')
            ->setRequired(true);
        
        yield ChoiceField::new('type', 'Type')
            ->setChoices([
                'Agence' => PickupPoint::TYPE_AGENCY,
                'Aéroport' => PickupPoint::TYPE_AIRPORT,
            ])
            ->setRequired(true)
            ->renderAsBadges([
                PickupPoint::TYPE_AGENCY => 'primary',
                PickupPoint::TYPE_AIRPORT => 'info',
            ]);
        
        yield AssociationField::new('city', 'Ville')
            ->autocomplete()
            ->setRequired(true);
        
        yield TextareaField::new('address', 'Adresse')
            ->hideOnIndex();
        
        yield TextField::new('phone', 'Téléphone');
        
        yield NumberField::new('latitude', 'Latitude')
            ->setNumDecimals(6)
            ->hideOnIndex();
        
        yield NumberField::new('longitude', 'Longitude')
            ->setNumDecimals(6)
            ->hideOnIndex();
        
        yield BooleanField::new('isActive', 'Actif');
    }
}