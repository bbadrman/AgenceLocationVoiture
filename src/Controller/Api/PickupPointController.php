<?php
namespace App\Controller\Api;

use App\Repository\PickupPointRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api', name: 'api_')]
class PickupPointController extends AbstractController
{
    public function __construct(
        private PickupPointRepository $pickupPointRepository
    ) {}

    /**
     * Liste tous les points de collecte actifs
     */
    #[Route('/pickup-points', name: 'pickup_points_list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $pickupPoints = $this->pickupPointRepository->findBy(
            ['isActive' => true],
            ['name' => 'ASC']
        );

        return $this->json($pickupPoints, 200, [], ['groups' => ['pickup_point:read']]);
    }

    /**
     * Points de collecte par ville
     */
    #[Route('/cities/{cityId}/pickup-points', name: 'city_pickup_points', methods: ['GET'])]
    public function getByCity(int $cityId): JsonResponse
    {
        $pickupPoints = $this->pickupPointRepository->findBy([
            'city' => $cityId,
            'isActive' => true
        ], ['name' => 'ASC']);

        return $this->json($pickupPoints, 200, [], ['groups' => ['pickup_point:read']]);
    }

    /**
     * Détails d'un point de collecte
     */
    #[Route('/pickup-points/{id}', name: 'pickup_point_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $pickupPoint = $this->pickupPointRepository->find($id);

        if (!$pickupPoint) {
            return $this->json(['error' => 'Point de collecte non trouvé'], 404);
        }

        return $this->json($pickupPoint, 200, [], ['groups' => ['pickup_point:read']]);
    }
}