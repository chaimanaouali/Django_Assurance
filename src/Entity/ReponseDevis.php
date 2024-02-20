<?php

namespace App\Entity;

use App\Repository\ReponseDevisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseDevisRepository::class)]
class ReponseDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\Choice(choices: ['en attente', 'refusé', 'en traitement', 'validé'], message: 'L\'état doit être "en attente", "refusé", "en traitement" ou "validé"')]
    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $decision = null;

    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_reglement = null;

    #[ORM\Column(length: 255)]
    private ?string $delai_reparation = null;

    #[ORM\Column(length: 255)]
    private ?string $duree_validite = null;

    #[Assert\File(mimeTypes: ['image/jpeg', 'image/png', 'application/pdf'])]
    #[ORM\Column(length: 255)]
    private ?string $documents = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?Devis $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDecision(): ?string
    {
        return $this->decision;
    }

    public function setDecision(string $decision): static
    {
        $this->decision = $decision;

        return $this;
    }

    public function getDateReglement(): ?\DateTimeInterface
    {
        return $this->date_reglement;
    }

    public function setDateReglement(\DateTimeInterface $date_reglement): static
    {
        $this->date_reglement = $date_reglement;

        return $this;
    }

    public function getDelaiReparation(): ?string
    {
        return $this->delai_reparation;
    }

    public function setDelaiReparation(string $delai_reparation): static
    {
        $this->delai_reparation = $delai_reparation;

        return $this;
    }

    public function getDureeValidite(): ?string
    {
        return $this->duree_validite;
    }

    public function setDureeValidite(string $duree_validite): static
    {
        $this->duree_validite = $duree_validite;

        return $this;
    }

    public function getDocuments(): ?string
    {
        return $this->documents;
    }

    public function setDocuments(string $documents): static
    {
        $this->documents = $documents;

        return $this;
    }

    public function getEmail(): ?Devis
    {
        return $this->email;
    }

    public function setEmail(?Devis $email): static
    {
        $this->email = $email;

        return $this;
    }
}
