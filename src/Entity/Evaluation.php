<?php

namespace App\Entity;

use App\Repository\EvaluationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EvaluationRepository::class)]
class Evaluation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom_client = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom_client = null;

    #[ORM\Column(length: 255)]
    private ?string $avis_client = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_eval = null;

    #[ORM\ManyToOne(inversedBy: 'evaluations')]
    private ?Mecanicien $mecanicien = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomClient(): ?string
    {
        return $this->nom_client;
    }

    public function setNomClient(string $nom_client): static
    {
        $this->nom_client = $nom_client;

        return $this;
    }

    public function getPrenomClient(): ?string
    {
        return $this->prenom_client;
    }

    public function setPrenomClient(string $prenom_client): static
    {
        $this->prenom_client = $prenom_client;

        return $this;
    }

    public function getAvisClient(): ?string
    {
        return $this->avis_client;
    }

    public function setAvisClient(string $avis_client): static
    {
        $this->avis_client = $avis_client;

        return $this;
    }

    public function getDateEval(): ?\DateTimeInterface
    {
        return $this->date_eval;
    }

    public function setDateEval(\DateTimeInterface $date_eval): static
    {
        $this->date_eval = $date_eval;

        return $this;
    }

    public function getMecanicien(): ?Mecanicien
    {
        return $this->mecanicien;
    }

    public function setMecanicien(?Mecanicien $mecanicien): static
    {
        $this->mecanicien = $mecanicien;

        return $this;
    }
}
