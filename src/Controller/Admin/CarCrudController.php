<?php
// src/Controller/Admin/CarCrudController.php

namespace App\Controller\Admin;

use App\Entity\Car;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use App\Form\CarImageType;

class CarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Car::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Voiture')
            ->setEntityLabelInPlural('Voitures')
            ->setDefaultSort(['brand' => 'ASC', 'model' => 'ASC'])
            ->setPageTitle('index', '🚗 Liste des voitures')
            ->setPageTitle('new', '➕ Ajouter une voiture')
            ->setPageTitle('edit', '✏️ Modifier la voiture');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        
        yield TextField::new('brand', 'Marque')
            ->setRequired(true);
        
        yield TextField::new('model', 'Modèle')
            ->setRequired(true);
        
        yield TextField::new('year', 'Année');
        
        yield TextField::new('registrationNumber', 'Immatriculation');
        
        yield IntegerField::new('seats', 'Nombre de places')
            ->setRequired(true);
        
        yield ChoiceField::new('fuelType', 'Type de carburant')
            ->setChoices([
                'Essence' => Car::FUEL_GASOLINE,
                'Diesel' => Car::FUEL_DIESEL,
                'Hybride' => Car::FUEL_HYBRID,
                'Électrique' => Car::FUEL_ELECTRIC,
            ])
            ->setRequired(true);
        
        yield ChoiceField::new('transmission', 'Transmission')
            ->setChoices([
                'Manuelle' => Car::TRANSMISSION_MANUAL,
                'Automatique' => Car::TRANSMISSION_AUTOMATIC,
            ])
            ->setRequired(true);
        
        yield MoneyField::new('pricePerDay', 'Prix par jour')
            ->setCurrency('MAD')
            ->setRequired(true);
        
        yield AssociationField::new('city', 'Ville')
            ->autocomplete()
            ->setRequired(true);
        
        yield TextareaField::new('description', 'Description')
            ->hideOnIndex();
        
        yield ArrayField::new('features', 'Équipements')
            ->hideOnIndex();
        
        yield ImageField::new('mainImage', 'Image principale')
            ->setBasePath('uploads/cars')
            ->setUploadDir('public/uploads/cars')
            ->setUploadedFileNamePattern('[uuid].[extension]')
            ->hideOnIndex();
        
        yield CollectionField::new('carImages', 'Galerie d\'images')
            ->setEntryType(CarImageType::class)
            ->hideOnIndex();
        
        yield BooleanField::new('isActive', 'Actif');
    }
}