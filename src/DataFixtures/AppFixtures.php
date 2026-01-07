<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\Booking;
use App\Entity\Car;
use App\Entity\City;
use App\Entity\Customer;
use App\Entity\PickupPoint;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Créer des villes
        $cities = [];
        $citiesData = [
            ['name' => 'Rabat', 'code' => 'RBT'],
            ['name' => 'Casablanca', 'code' => 'CSA'],
            ['name' => 'Marrakech', 'code' => 'MRK'],
            ['name' => 'Tanger', 'code' => 'TNG'],
            ['name' => 'Agadir', 'code' => 'AGA'],
        ];

        foreach ($citiesData as $cityData) {
            $city = new City();
            $city->setName($cityData['name'])
                ->setCode($cityData['code'])
                ->setIsActive(true);
            
            $manager->persist($city);
            $cities[] = $city;
        }

        // Créer des points de collecte
        $pickupPoints = [];
        
        foreach ($cities as $city) {
            // Agence
            $agency = new PickupPoint();
            $agency->setName('Agence ' . $city->getName() . ' Centre')
                ->setType(PickupPoint::TYPE_AGENCY)
                ->setCity($city)
                ->setAddress('Avenue principale, ' . $city->getName())
                ->setPhone('+212 5 37 XX XX XX')
                ->setIsActive(true);
            
            $manager->persist($agency);
            $pickupPoints[] = $agency;
            
            // Aéroport (sauf pour Tanger)
            if ($city->getName() !== 'Tanger') {
                $airport = new PickupPoint();
                $airport->setName('Aéroport ' . $city->getName())
                    ->setType(PickupPoint::TYPE_AIRPORT)
                    ->setCity($city)
                    ->setAddress('Aéroport International de ' . $city->getName())
                    ->setPhone('+212 5 37 YY YY YY')
                    ->setIsActive(true);
                
                $manager->persist($airport);
                $pickupPoints[] = $airport;
            }
        }

        // Créer des voitures
        $cars = [];
        $carsData = [
            ['brand' => 'Peugeot', 'model' => '208', 'seats' => 5, 'fuel' => Car::FUEL_GASOLINE, 'price' => '250'],
            ['brand' => 'Renault', 'model' => 'Clio', 'seats' => 5, 'fuel' => Car::FUEL_DIESEL, 'price' => '280'],
            ['brand' => 'Dacia', 'model' => 'Sandero', 'seats' => 5, 'fuel' => Car::FUEL_GASOLINE, 'price' => '220'],
            ['brand' => 'Volkswagen', 'model' => 'Golf', 'seats' => 5, 'fuel' => Car::FUEL_DIESEL, 'price' => '350'],
            ['brand' => 'BMW', 'model' => 'Serie 3', 'seats' => 5, 'fuel' => Car::FUEL_HYBRID, 'price' => '550'],
            ['brand' => 'Mercedes', 'model' => 'Classe A', 'seats' => 5, 'fuel' => Car::FUEL_DIESEL, 'price' => '600'],
            ['brand' => 'Toyota', 'model' => 'Yaris', 'seats' => 5, 'fuel' => Car::FUEL_HYBRID, 'price' => '320'],
            ['brand' => 'Hyundai', 'model' => 'i10', 'seats' => 4, 'fuel' => Car::FUEL_GASOLINE, 'price' => '200'],
            ['brand' => 'Fiat', 'model' => '500', 'seats' => 4, 'fuel' => Car::FUEL_GASOLINE, 'price' => '210'],
            ['brand' => 'Peugeot', 'model' => '3008', 'seats' => 7, 'fuel' => Car::FUEL_DIESEL, 'price' => '450'],
        ];

        foreach ($carsData as $index => $carData) {
            $car = new Car();
            $car->setBrand($carData['brand'])
                ->setModel($carData['model'])
                ->setYear('2023')
                ->setSeats($carData['seats'])
                ->setFuelType($carData['fuel'])
                ->setTransmission($index % 2 === 0 ? Car::TRANSMISSION_MANUAL : Car::TRANSMISSION_AUTOMATIC)
                ->setPricePerDay($carData['price'])
                ->setCity($cities[array_rand($cities)])
                ->setDescription('Voiture en excellent état, parfaite pour vos déplacements')
                ->setFeatures(['GPS', 'Climatisation', 'Bluetooth', 'Régulateur de vitesse'])
                ->setRegistrationNumber(strtoupper(substr(md5($carData['brand'] . $carData['model']), 0, 6)))
                ->setIsActive(true);
            
            $manager->persist($car);
            $cars[] = $car;
        }

        // Créer des clients
        $customers = [];
        $customersData = [
            ['firstName' => 'Ahmed', 'lastName' => 'Benali', 'email' => 'ahmed.benali@example.com', 'phone' => '+212 6 12 34 56 78'],
            ['firstName' => 'Fatima', 'lastName' => 'Zahra', 'email' => 'fatima.zahra@example.com', 'phone' => '+212 6 23 45 67 89'],
            ['firstName' => 'Mohamed', 'lastName' => 'Alami', 'email' => 'mohamed.alami@example.com', 'phone' => '+212 6 34 56 78 90'],
            ['firstName' => 'Salma', 'lastName' => 'Idrissi', 'email' => 'salma.idrissi@example.com', 'phone' => '+212 6 45 67 89 01'],
            ['firstName' => 'Youssef', 'lastName' => 'Tazi', 'email' => 'youssef.tazi@example.com', 'phone' => '+212 6 56 78 90 12'],
        ];

        foreach ($customersData as $customerData) {
            $customer = new Customer();
            $customer->setFirstName($customerData['firstName'])
                ->setLastName($customerData['lastName'])
                ->setEmail($customerData['email'])
                ->setPhone($customerData['phone']);
            
            $manager->persist($customer);
            $customers[] = $customer;
        }

        // Créer quelques réservations
        for ($i = 0; $i < 10; $i++) {
            $startDate = new \DateTime('+' . rand(1, 30) . ' days');
            $endDate = (clone $startDate)->modify('+' . rand(2, 10) . ' days');
            
            $car = $cars[array_rand($cars)];
            $pickupPoint = $pickupPoints[array_rand($pickupPoints)];
            
            $booking = new Booking();
            $booking->setCar($car)
                ->setCustomer($customers[array_rand($customers)])
                ->setPickupPoint($pickupPoint)
                ->setReturnPoint($pickupPoint)
                ->setStartDate($startDate)
                ->setEndDate($endDate)
                ->setPricePerDay($car->getPricePerDay())
                ->setStatus([
                    Booking::STATUS_PENDING,
                    Booking::STATUS_CONFIRMED,
                    Booking::STATUS_CONFIRMED,
                    Booking::STATUS_CONFIRMED,
                ][array_rand([0, 1, 2, 3])]);
            
            $booking->calculateNumberOfDays();
            $booking->calculateTotalPrice();
            
            $manager->persist($booking);
        }

        $manager->flush();
    }
}