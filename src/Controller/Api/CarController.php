<?php
namespace App\Controller\Api;

use App\DTO\CarSearchRequest;
use App\Repository\CarRepository;
use App\Service\CarAvailabilityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/cars', name: 'api_cars_')]
class CarController extends AbstractController
{
    public function __construct(
        private CarRepository $carRepository,
        private CarAvailabilityService $availabilityService,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    /**
     * Recherche de voitures disponibles
     * 
     * Query params:
     * - cityId (required): ID de la ville
     * - startDate (required): Date de début (Y-m-d H:i:s)
     * - endDate (required): Date de fin (Y-m-d H:i:s)
     * - pickupPointId (optional): ID du point de collecte
     * - brand (optional): Marque
     * - seats (optional): Nombre de places minimum
     * - fuelType (optional): Type de carburant
     * - transmission (optional): Type de transmission
     * - minPrice (optional): Prix minimum par jour
     * - maxPrice (optional): Prix maximum par jour
     */
    #[Route('/search', name: 'search', methods: ['GET'])]
    public function search(Request $request): JsonResponse
    {
        // Créer le DTO depuis les paramètres de requête
        $searchRequest = new CarSearchRequest();
        $searchRequest->setCityId($request->query->get('cityId'))
            ->setPickupPointId($request->query->get('pickupPointId'))
            ->setStartDate($request->query->get('startDate'))
            ->setEndDate($request->query->get('endDate'))
            ->setBrand($request->query->get('brand'))
            ->setSeats($request->query->get('seats'))
            ->setFuelType($request->query->get('fuelType'))
            ->setTransmission($request->query->get('transmission'))
            ->setMinPrice($request->query->get('minPrice'))
            ->setMaxPrice($request->query->get('maxPrice'));

        // Valider la requête
        $errors = $this->validator->validate($searchRequest);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], 400);
        }

        try {
            $startDate = new \DateTime($searchRequest->getStartDate());
            $endDate = new \DateTime($searchRequest->getEndDate());

            // Préparer les filtres
            $filters = array_filter([
                'brand' => $searchRequest->getBrand(),
                'seats' => $searchRequest->getSeats(),
                'fuelType' => $searchRequest->getFuelType(),
                'transmission' => $searchRequest->getTransmission(),
                'minPrice' => $searchRequest->getMinPrice(),
                'maxPrice' => $searchRequest->getMaxPrice(),
            ]);

            // Rechercher les voitures disponibles
            $cars = $this->availabilityService->getAvailableCars(
                $searchRequest->getCityId(),
                $startDate,
                $endDate,
                $filters
            );

            // Calculer le nombre de jours pour chaque voiture
            $numberOfDays = $this->availabilityService->calculateNumberOfDays($startDate, $endDate);

            // Enrichir les données avec le prix total
            $carsData = array_map(function($car) use ($numberOfDays) {
                $carArray = json_decode(
                    $this->serializer->serialize($car, 'json', ['groups' => ['car:read']]),
                    true
                );
                $carArray['numberOfDays'] = $numberOfDays;
                $carArray['totalPrice'] = $this->availabilityService->calculateTotalPrice(
                    (float)$car->getPricePerDay(),
                    $numberOfDays
                );
                return $carArray;
            }, $cars);

            return $this->json([
                'cars' => array_values($carsData),
                'total' => count($carsData),
                'filters' => $filters,
                'period' => [
                    'startDate' => $startDate->format('Y-m-d H:i:s'),
                    'endDate' => $endDate->format('Y-m-d H:i:s'),
                    'numberOfDays' => $numberOfDays
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Erreur lors de la recherche',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Liste toutes les voitures actives (sans filtre de disponibilité)
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(Request $request): JsonResponse
    {
        $cityId = $request->query->get('cityId');
        
        $criteria = ['isActive' => true];
        if ($cityId) {
            $criteria['city'] = $cityId;
        }

        $cars = $this->carRepository->findBy($criteria, ['brand' => 'ASC', 'model' => 'ASC']);

        return $this->json($cars, 200, [], ['groups' => ['car:read']]);
    }

    /**
     * Détails d'une voiture
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id, Request $request): JsonResponse
    {
        $car = $this->carRepository->find($id);

        if (!$car) {
            return $this->json(['error' => 'Voiture non trouvée'], 404);
        }

        $carData = json_decode(
            $this->serializer->serialize($car, 'json', ['groups' => ['car:read']]),
            true
        );

        // Si des dates sont fournies, calculer le prix total
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        if ($startDate && $endDate) {
            try {
                $start = new \DateTime($startDate);
                $end = new \DateTime($endDate);
                $numberOfDays = $this->availabilityService->calculateNumberOfDays($start, $end);
                
                $carData['numberOfDays'] = $numberOfDays;
                $carData['totalPrice'] = $this->availabilityService->calculateTotalPrice(
                    (float)$car->getPricePerDay(),
                    $numberOfDays
                );
                $carData['isAvailable'] = $this->availabilityService->isCarAvailable($car, $start, $end);
            } catch (\Exception $e) {
                // Ignorer les erreurs de date
            }
        }

        return $this->json($carData);
    }

    /**
     * Vérifie la disponibilité d'une voiture
     */
    #[Route('/{id}/availability', name: 'check_availability', methods: ['GET'])]
    public function checkAvailability(int $id, Request $request): JsonResponse
    {
        $car = $this->carRepository->find($id);

        if (!$car) {
            return $this->json(['error' => 'Voiture non trouvée'], 404);
        }

        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');

        if (!$startDate || !$endDate) {
            return $this->json(['error' => 'Les dates sont requises'], 400);
        }

        try {
            $start = new \DateTime($startDate);
            $end = new \DateTime($endDate);

            $isAvailable = $this->availabilityService->isCarAvailable($car, $start, $end);

            return $this->json([
                'carId' => $id,
                'available' => $isAvailable,
                'period' => [
                    'startDate' => $start->format('Y-m-d H:i:s'),
                    'endDate' => $end->format('Y-m-d H:i:s')
                ]
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Erreur lors de la vérification',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Récupère les marques disponibles
     */
    #[Route('/brands/list', name: 'brands', methods: ['GET'])]
    public function getBrands(): JsonResponse
    {
        $brands = $this->carRepository->createQueryBuilder('c')
            ->select('DISTINCT c.brand')
            ->where('c.isActive = true')
            ->orderBy('c.brand', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();

        return $this->json(['brands' => $brands]);
    }

    /**
     * Récupère les types de carburant disponibles
     */
    #[Route('/fuel-types/list', name: 'fuel_types', methods: ['GET'])]
    public function getFuelTypes(): JsonResponse
    {
        return $this->json([
            'fuelTypes' => [
                ['value' => 'gasoline', 'label' => 'Essence'],
                ['value' => 'diesel', 'label' => 'Diesel'],
                ['value' => 'hybrid', 'label' => 'Hybride'],
                ['value' => 'electric', 'label' => 'Électrique']
            ]
        ]);
    }
}