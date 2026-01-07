<?php
namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class BookingRequest
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private ?int $carId = null;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    private ?int $pickupPointId = null;

    #[Assert\Type('integer')]
    private ?int $returnPointId = null;

    #[Assert\NotBlank]
    #[Assert\DateTime]
    private ?string $startDate = null;

    #[Assert\NotBlank]
    #[Assert\DateTime]
    private ?string $endDate = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 100)]
    private ?string $lastName = null;

    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[0-9\s\+\-\(\)]+$/', message: 'Numéro de téléphone invalide')]
    private ?string $phone = null;

    #[Assert\Length(max: 500)]
    private ?string $notes = null;

    // Getters & Setters
    public function getCarId(): ?int
    {
        return $this->carId;
    }

    public function setCarId(?int $carId): self
    {
        $this->carId = $carId;
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

    public function getReturnPointId(): ?int
    {
        return $this->returnPointId;
    }

    public function setReturnPointId(?int $returnPointId): self
    {
        $this->returnPointId = $returnPointId;
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;
        return $this;
    }
}