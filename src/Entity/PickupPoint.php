<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PickupPointRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PickupPointRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['pickup_point:read']],
    denormalizationContext: ['groups' => ['pickup_point:write']]
)]
class PickupPoint
{
    public const TYPE_AGENCY = 'agency';
    public const TYPE_AIRPORT = 'airport';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['pickup_point:read', 'booking:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    #[Groups(['pickup_point:read', 'pickup_point:write', 'booking:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 20)]
    #[Groups(['pickup_point:read', 'pickup_point:write', 'booking:read'])]
    private ?string $type = null; // 'agency' ou 'airport'

    #[ORM\Column(type: 'text', nullable: true)]
    #[Groups(['pickup_point:read', 'pickup_point:write'])]
    private ?string $address = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Groups(['pickup_point:read', 'pickup_point:write'])]
    private ?string $phone = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['pickup_point:read', 'pickup_point:write'])]
    private ?float $latitude = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['pickup_point:read', 'pickup_point:write'])]
    private ?float $longitude = null;

    #[ORM\Column]
    #[Groups(['pickup_point:read', 'pickup_point:write'])]
    private ?bool $isActive = true;

    #[ORM\ManyToOne(inversedBy: 'pickupPoints')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['pickup_point:read', 'pickup_point:write'])]
    private ?City $city = null;

    // Getters & Setters...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;
        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;
        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(?float $latitude): static
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(?float $longitude): static
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;
        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;
        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}