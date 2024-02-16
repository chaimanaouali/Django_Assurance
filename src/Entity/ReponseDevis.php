<?php

namespace App\Entity;

use App\Repository\ReponseDevisRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseDevisRepository::class)]
class ReponseDevis
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", name: "id_rep")]
    private $idRep;

    #[ORM\Column(type: "string", length: 255)]
    private $etat;

    #[ORM\Column(type: "string", length: 255)]
    private $decision;

    #[ORM\Column(type: "date", name: "date_reglement")]
    private $dateReglement;

    #[ORM\Column(type: "string", length: 255, name: "delai_reparation")]
    private $delaiReparation;

    #[ORM\Column(type: "string", length: 255, name: "duree_validite")]
    private $dureeValidite;

    #[ORM\Column(type: "string", length: 255)]
    private $documents;

    public function getIdRep(): ?int
    {
        return $this->idRep;
    }

    public function setIdRep(int $idRep): self
    {
        $this->idRep = $idRep;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getDecision(): ?string
    {
        return $this->decision;
    }

    public function setDecision(string $decision): self
    {
        $this->decision = $decision;
        return $this;
    }

    public function getDateReglement(): ?\DateTimeInterface
    {
        return $this->dateReglement;
    }

    public function setDateReglement(\DateTimeInterface $dateReglement): self
    {
        $this->dateReglement = $dateReglement;
        return $this;
    }

    public function getDelaiReparation(): ?string
    {
        return $this->delaiReparation;
    }

    public function setDelaiReparation(string $delaiReparation): self
    {
        $this->delaiReparation = $delaiReparation;
        return $this;
    }

    public function getDureeValidite(): ?string
    {
        return $this->dureeValidite;
    }

    public function setDureeValidite(string $dureeValidite): self
    {
        $this->dureeValidite = $dureeValidite;
        return $this;
    }

    public function getDocuments(): ?string
    {
        return $this->documents;
    }

    public function setDocuments(string $documents): self
    {
        $this->documents = $documents;
        return $this;
    }
}
