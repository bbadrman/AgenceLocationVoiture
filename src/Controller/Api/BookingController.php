<?php
namespace App\Controller\Api;

use App\DTO\BookingRequest;
use App\DTO\BookingResponse;
use App\Service\BookingService;
use App\Repository\BookingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/bookings', name: 'api_bookings_')]
class BookingController extends AbstractController
{
    public function __construct(
        private BookingService $bookingService,
        private BookingRepository $bookingRepository,
        private SerializerInterface $serializer,
        private ValidatorInterface $validator
    ) {}

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        try {
            $bookingRequest = $this->serializer->deserialize(
                $request->getContent(),
                BookingRequest::class,
                'json'
            );

            $errors = $this->validator->validate($bookingRequest);
            if (count($errors) > 0) {
                $errorMessages = [];
                foreach ($errors as $error) {
                    $errorMessages[$error->getPropertyPath()] = $error->getMessage();
                }
                return $this->json(['errors' => $errorMessages], 400);
            }

            $booking = $this->bookingService->createBooking($bookingRequest);
            
            // Utiliser le BookingResponse DTO
            $response = BookingResponse::fromEntity($booking);

            return $this->json([
                'message' => 'Réservation créée avec succès',
                'booking' => $response
            ], 201);

        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'error' => 'Données invalides',
                'message' => $e->getMessage()
            ], 400);
        } catch (\Exception $e) {
            return $this->json([
                'error' => 'Erreur lors de la création de la réservation',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    #[Route('/{bookingNumber}', name: 'show', methods: ['GET'])]
    public function show(string $bookingNumber): JsonResponse
    {
        $booking = $this->bookingService->getBookingByNumber($bookingNumber);

        if (!$booking) {
            return $this->json(['error' => 'Réservation non trouvée'], 404);
        }

        // Utiliser le BookingResponse DTO
        $response = BookingResponse::fromEntity($booking);

        return $this->json($response);
    }
}