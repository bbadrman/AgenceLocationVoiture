<?php
// src/Controller/Admin/BookingCrudController.php

namespace App\Controller\Admin;

use App\Entity\Booking;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\ChoiceFilter;
use EasyCorp\Bundle\EasyAdminBundle\Filter\DateTimeFilter;

class BookingCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Booking::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Réservation')
            ->setEntityLabelInPlural('Réservations')
            ->setDefaultSort(['createdAt' => 'DESC'])
            ->setPageTitle('index', '📅 Liste des réservations')
            ->setPageTitle('new', '➕ Nouvelle réservation')
            ->setPageTitle('edit', '✏️ Modifier la réservation')
            ->setPageTitle('detail', '🔍 Détails de la réservation');
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        
        yield TextField::new('bookingNumber', 'Numéro')
            ->hideOnForm();
        
        yield AssociationField::new('car', 'Voiture')
            ->autocomplete()
            ->setRequired(true);
        
        yield AssociationField::new('customer', 'Client')
            ->autocomplete()
            ->setRequired(true);
        
        yield AssociationField::new('pickupPoint', 'Point de prise en charge')
            ->autocomplete()
            ->setRequired(true);
        
        yield AssociationField::new('returnPoint', 'Point de retour')
            ->autocomplete();
        
        yield DateTimeField::new('startDate', 'Date de début')
            ->setRequired(true);
        
        yield DateTimeField::new('endDate', 'Date de fin')
            ->setRequired(true);
        
        yield IntegerField::new('numberOfDays', 'Nombre de jours')
            ->hideOnForm();
        
        yield MoneyField::new('pricePerDay', 'Prix par jour')
            ->setCurrency('MAD')
            ->hideOnForm();
        
        yield MoneyField::new('totalPrice', 'Prix total')
            ->setCurrency('MAD')
            ->hideOnForm();
        
        yield ChoiceField::new('status', 'Statut')
            ->setChoices([
                'En attente' => Booking::STATUS_PENDING,
                'Confirmée' => Booking::STATUS_CONFIRMED,
                'Annulée' => Booking::STATUS_CANCELLED,
                'Terminée' => Booking::STATUS_COMPLETED,
            ])
            ->renderAsBadges([
                Booking::STATUS_PENDING => 'warning',
                Booking::STATUS_CONFIRMED => 'success',
                Booking::STATUS_CANCELLED => 'danger',
                Booking::STATUS_COMPLETED => 'info',
            ]);
        
        yield TextareaField::new('notes', 'Notes')
            ->hideOnIndex();
        
        yield DateTimeField::new('createdAt', 'Date de création')
            ->hideOnForm();
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(ChoiceFilter::new('status')->setChoices([
                'En attente' => Booking::STATUS_PENDING,
                'Confirmée' => Booking::STATUS_CONFIRMED,
                'Annulée' => Booking::STATUS_CANCELLED,
                'Terminée' => Booking::STATUS_COMPLETED,
            ]))
            ->add(DateTimeFilter::new('startDate'))
            ->add(DateTimeFilter::new('endDate'))
            ->add('car')
            ->add('customer');
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}