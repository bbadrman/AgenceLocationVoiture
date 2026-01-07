<?php
namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['car:read']],
    denormalizationContext: ['groups' => ['car:write']]
)]
class Car
{
    public const FUEL_GASOLINE = 'gasoline';
    public const FUEL_DIESEL = 'diesel';
    public const FUEL_HYBRID = 'hybrid';
    public const FUEL_ELECTRIC = 'electric';

    public const TRANSMISSION_MANUAL = 'manual';
    public const TRANSMISSION_AUTOMATIC = 'automatic';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:read', 'booking:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['car:read', 'car:write', 'booking:read'])]
    private ?string $brand = null;

    #[ORM\Column(length: 100)]
    #[Groups(['car:read', 'car:write', 'booking:read'])]
    private ?string $model = null;

    #[ORM\Column(length: 4, nullable: true)]
    #[Groups(['car:read', 'car:write'])]
    private ?string $year = null;

    #[ORM\Column]
    #[Groups(['car:read', 'car:write', 'booking:read'])]
    private ?int $seats = null;

    #[ORM\Column(length: 20)]
    #[Groups(['car:read', 'car:write', 'booking:read'])]
    private ?string $fuelType = null;

    #[ORM\Column(length: 20)]
    #[Groups(['car:read', 'car:write', 'booking:read'])]
    private ?string $transmission = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Groups(['car:read', 'car:write', 'booking:read'])]
    private ?string $pricePerDay = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['car:read', 'car:write'])]
    private ?string $description = null;

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['car:read', 'car:write'])]
    private ?array $features = []; // ["GPS", "Climatisation", "Bluetooth"]

    #[ORM\Column(type: Types::JSON, nullable: true)]
    #[Groups(['car:read', 'car:write'])]
    private ?array $images = []; // ["image1.jpg", "image2.jpg"]

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['car:read', 'car:write'])]
    private ?string $mainImage = null;

    #[ORM\Column]
    #[Groups(['car:read', 'car:write'])]
    private ?bool $isActive = true;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['car:read', 'car:write'])]
    private ?City $city = null;

    #[ORM\OneToMany(mappedBy: 'car', targetEntity: Booking::class)]
    private Collection $bookings;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['car:read', 'car:write'])]
    private ?string $registrationNumber = null;

    public function __construct()
    {
        $this->bookings = new ArrayCollection();
    }

    // Getters & Setters...
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;
        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;
        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(?string $year): static
    {
        $this->year = $year;
        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): static
    {
        $this->seats = $seats;
        return $this;
    }

    public function getFuelType(): ?string
    {
        return $this->fuelType;
    }

    public function setFuelType(string $fuelType): static
    {
        $this->fuelType = $fuelType;
        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): static
    {
        $this->transmission = $transmission;
        return $this;
    }

    public function getPricePerDay(): ?string
    {
        return $this->pricePerDay;
    }

    public function setPricePerDay(string $pricePerDay): static
    {
        $this->pricePerDay = $pricePerDay;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getFeatures(): ?array
    {
        return $this->features;
    }

    public function setFeatures(?array $features): static
    {
        $this->features = $features;
        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): static
    {
        $this->images = $images;
        return $this;
    }

    public function getMainImage(): ?string
    {
        return $this->mainImage;
    }

    public function setMainImage(?string $mainImage): static
    {
        $this->mainImage = $mainImage;
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

    public function getBookings(): Collection
    {
        return $this->bookings;
    }

    public function addBooking(Booking $booking): static
    {
        if (!$this->bookings->contains($booking)) {
            $this->bookings->add($booking);
            $booking->setCar($this);
        }
        return $this;
    }

    public function removeBooking(Booking $booking): static
    {
        if ($this->bookings->removeElement($booking)) {
            if ($booking->getCar() === $this) {
                $booking->setCar(null);
            }
        }
        return $this;
    }

    public function getRegistrationNumber(): ?string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber(?string $registrationNumber): static
    {
        $this->registrationNumber = $registrationNumber;
        return $this;
    }

    public function __toString(): string
    {
        return sprintf('%s %s', $this->brand, $this->model);
    }
}