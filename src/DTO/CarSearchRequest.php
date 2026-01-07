<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CarSearchRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private ?int $cityId = null;

    #[Assert\Type('integer')]
    private ?int $pickupPointId = null;

    #[Assert\NotBlank]
    #[Assert\DateTime]
    private ?string $startDate = null;

    #[Assert\NotBlank]
    #[Assert\DateTime]
    private ?string $endDate = null;

    private ?string $brand = null;

    #[Assert\Type('integer')]
    #[Assert\Positive]
    private ?int $seats = null;

    private ?string $fuelType = null;

    private ?string $transmission = null;

    #[Assert\Type('float')]
    #[Assert\PositiveOrZero]
    private ?float $minPrice = null;

    #[Assert\Type('float')]
    #[Assert\PositiveOrZero]
    private ?float $maxPrice = null;

    // Getters & Setters
    public function getCityId(): ?int
    {
        return $this->cityId;
    }

    public function setCityId(?int $cityId): self
    {
        $this->cityId = $cityId;
        return $this;
    }

    public function getPickupPointId(): ?int
    {
        return $this->pickupPointId;
    }

    public function setPickupPointId(?int $pickupPointId): self
    {
        $this->pickupPointId = $pickupPointId;
        return $this;
    }

    public function getStartDate(): ?string
    {
        return $this->startDate;
    }

    public function setStartDate(?string $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    public function getEndDate(): ?string
    {
        return $this->endDate;
    }

    public function setEndDate(?string $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;
        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(?int $seats): self
    {
        $this->seats = $seats;
        return $this;
    }

    public function getFuelType(): ?string
    {
        return $this->fuelType;
    }

    public function setFuelType(?string $fuelType): self
    {
        $this->fuelType = $fuelType;
        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(?string $transmission): self
    {
        $this->transmission = $transmission;
        return $this;
    }

    public function getMinPrice(): ?float
    {
        return $this->minPrice;
    }

    public function setMinPrice(?float $minPrice): self
    {
        $this->minPrice = $minPrice;
        return $this;
    }

    public function getMaxPrice(): ?float
    {
        return $this->maxPrice;
    }

    public function setMaxPrice(?float $maxPrice): self
    {
        $this->maxPrice = $maxPrice;
        return $this;
    }
}