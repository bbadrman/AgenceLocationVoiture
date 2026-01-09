<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class CarImage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['car:read'])]
    private ?int $id = null;


    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['car:read'])]
    private ?string $imageName = null;

    private ?File $file = null;

    #[ORM\ManyToOne(inversedBy: 'carImages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Car $car = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(?string $imageName): static
    {
        $this->imageName = $imageName;
        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function upload(): void
    {
        if (null === $this->file) {
            return;
        }

        // Generate a unique name
        $originalFilename = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$this->file->guessExtension();

        // Move the file to the directory
        // We will move it to 'public/uploads/cars'
        try {
            $this->file->move(
                __DIR__.'/../../public/uploads/cars',
                $newFilename
            );
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            return;
        }

        $this->imageName = $newFilename;
    }


    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): static
    {
        $this->car = $car;
        return $this;
    }
}
