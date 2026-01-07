<?php
namespace App\Controller\Api;

use App\Repository\CityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/cities', name: 'api_cities_')]
class CityController extends AbstractController
{
    public function __construct(
        private CityRepository $cityRepository
    ) {}

    /**
     * Liste toutes les villes actives
     */
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(): JsonResponse
    {
        $cities = $this->cityRepository->findBy(
            ['isActive' => true],
            ['name' => 'ASC']
        );

        return $this->json($cities, 200, [], ['groups' => ['city:read']]);
    }

    /**
     * Détails d'une ville
     */
    #[Route('/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $city = $this->cityRepository->find($id);

        if (!$city) {
            return $this->json(['error' => 'Ville non trouvée'], 404);
        }

        return $this->json($city, 200, [], ['groups' => ['city:read']]);
    }
}