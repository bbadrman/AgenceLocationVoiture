<?php
namespace App\DTO;

use App\Entity\Booking;

class BookingResponse
{
    private string $bookingNumber;
    private int $carId;
    private string $carName;
    private string $customerName;
    private string $customerEmail;
    private string $customerPhone;
    private string $pickupPointName;
    private ?string $returnPointName;
    private string $startDate;
    private string $endDate;
    private int $numberOfDays;
    private string $pricePerDay;
    private string $totalPrice;
    private string $status;
    private string $createdAt;

    public static function fromEntity(Booking $booking): self
    {
        $response = new self();
        
        $response->bookingNumber = $booking->getBookingNumber();
        $response->carId = $booking->getCar()->getId();
        $response->carName = sprintf(
            '%s %s',
            $booking->getCar()->getBrand(),
            $booking->getCar()->getModel()
        );
        $response->customerName = $booking->getCustomer()->getFullName();
        $response->customerEmail = $booking->getCustomer()->getEmail();
        $response->customerPhone = $booking->getCustomer()->getPhone();
        $response->pickupPointName = $booking->getPickupPoint()->getName();
        $response->returnPointName = $booking->getReturnPoint()?->getName();
        $response->startDate = $booking->getStartDate()->format('Y-m-d H:i:s');
        $response->endDate = $booking->getEndDate()->format('Y-m-d H:i:s');
        $response->numberOfDays = $booking->getNumberOfDays();
        $response->pricePerDay = $booking->getPricePerDay();
        $response->totalPrice = $booking->getTotalPrice();
        $response->status = $booking->getStatus();
        $response->createdAt = $booking->getCreatedAt()->format('Y-m-d H:i:s');

        return $response;
    }

    // Getters
    public function getBookingNumber(): string
    {
        return $this->bookingNumber;
    }

    public function getCarId(): int
    {
        return $this->carId;
    }

    public function getCarName(): string
    {
        return $this->carName;
    }

    public function getCustomerName(): string
    {
        return $this->customerName;
    }

    public function getCustomerEmail(): string
    {
        return $this->customerEmail;
    }

    public function getCustomerPhone(): string
    {
        return $this->customerPhone;
    }

    public function getPickupPointName(): string
    {
        return $this->pickupPointName;
    }

    public function getReturnPointName(): ?string
    {
        return $this->returnPointName;
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getEndDate(): string
    {
        return $this->endDate;
    }

    public function getNumberOfDays(): int
    {
        return $this->numberOfDays;
    }

    public function getPricePerDay(): string
    {
        return $this->pricePerDay;
    }

    public function getTotalPrice(): string
    {
        return $this->totalPrice;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }
}