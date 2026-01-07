<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CityRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['city:read']],
    denormalizationContext: ['groups' => ['city:write']]
)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['city:read', 'pickup_point:read', 'car:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Groups(['city:read', 'city:write', 'pickup_point:read'])]
    private ?string $name = null;

    #[ORM\Column(length: 10)]
    #[Groups(['city:read', 'city:write'])]
    private ?string $code = null;

    #[ORM\Column]
    #[Groups(['city:read', 'city:write'])]
    private ?bool $isActive = true;

    #[ORM\OneToMany(mappedBy: 'city', targetEntity: PickupPoint::class, orphanRemoval: true)]
    #[Groups(['city:read'])]
    private Collection $pickupPoints;

    public function __construct()
    {
        $this->pickupPoints = new ArrayCollection();
    }

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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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

    public function getPickupPoints(): Collection
    {
        return $this->pickupPoints;
    }

    public function addPickupPoint(PickupPoint $pickupPoint): static
    {
        if (!$this->pickupPoints->contains($pickupPoint)) {
            $this->pickupPoints->add($pickupPoint);
            $pickupPoint->setCity($this);
        }

        return $this;
    }

    public function removePickupPoint(PickupPoint $pickupPoint): static
    {
        if ($this->pickupPoints->removeElement($pickupPoint)) {
            if ($pickupPoint->getCity() === $this) {
                $pickupPoint->setCity(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->name ?? '';
    }
}
