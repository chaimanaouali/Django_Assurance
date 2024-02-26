<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
#[ORM\Entity(repositoryClass: VoitureRepository::class)]
#[UniqueEntity(fields: ['matricule'], message: 'This registration deja utilise.')]

class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $marque = null;

    #[ORM\Column]
    private ?float $prix_voiture = null;

    #[ORM\Column]
    private ?int $puissance = null;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    private ?User $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getPrixVoiture(): ?float
    {
        return $this->prix_voiture;
    }

    public function setPrixVoiture(float $prix_voiture): static
    {
        $this->prix_voiture = $prix_voiture;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): static
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getEmail(): ?User
    {
        return $this->email;
    }

    public function setEmail(?User $email): static
    {
        $this->email = $email;

        return $this;
    }
}
