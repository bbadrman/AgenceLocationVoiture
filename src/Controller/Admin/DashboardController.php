<?php
// src/Controller/Admin/DashboardController.php

namespace App\Controller\Admin;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\City;
use App\Entity\Customer;
use App\Entity\PickupPoint;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private AdminUrlGenerator $adminUrlGenerator
    ) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Rediriger vers la liste des réservations par défaut
        $url = $this->adminUrlGenerator
            ->setController(BookingCrudController::class)
            ->generateUrl();

        return $this->redirect($url);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('🚗 Location de Voitures')
            ->setFaviconPath('favicon.ico');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        
        yield MenuItem::section('📅 Réservations');
        yield MenuItem::linkToCrud('Réservations', 'fa fa-calendar-check', Booking::class);
        
        yield MenuItem::section('🚗 Gestion des voitures');
        yield MenuItem::linkToCrud('Voitures', 'fa fa-car', Car::class);
        yield MenuItem::linkToCrud('Villes', 'fa fa-city', City::class);
        yield MenuItem::linkToCrud('Points de collecte', 'fa fa-map-marker-alt', PickupPoint::class);
        
        yield MenuItem::section('👥 Clients');
        yield MenuItem::linkToCrud('Clients', 'fa fa-users', Customer::class);

        yield MenuItem::section('🔗 Liens externes');
        yield MenuItem::linkToUrl('Voir le site', 'fa fa-globe', '/');
        yield MenuItem::linkToUrl('API Documentation', 'fa fa-book', '/api/docs');
    }
}