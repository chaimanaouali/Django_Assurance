<?php

namespace App\Entity;

use App\Repository\TraitementRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TraitementRepository::class)]
#[Broadcast]
class Traitement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

  
    #[ORM\Column]
    private ?int $id_constat = null;
 
   
    #[ORM\Column()]
    private ?\DateTime $date_taitement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotEqualTo('aaa')]
    #[Assert\Length(max:30)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+(?: [a-zA-Z]+)* ?$/',
        message: 'The value {{ value }} is not a valid {{ type }}.'
    )]
    private ?string $responsable = null;

    #[ORM\Column(length: 255)]
    private ?string $statut = null;
    
    #[ORM\Column(length: 255)]
    private ?string $remarque = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdConstat(): ?int
    {
        return $this->id_constat;
    }

    public function setIdConstat(?int $id_constat): static
    {
        $this->id_constat = $id_constat;

        return $this;
    }

    public function getDateTaitement(): ?\DateTime
    {
        return $this->date_taitement;
    }

    public function setDateTaitement(\DateTime $date_taitement): static
    {
        $this->date_taitement = $date_taitement;

        return $this;
    }

    public function getResponsable(): ?string
    {
        return $this->responsable;
    }

    public function setResponsable(string $responsable): static
    {
        $this->responsable = $responsable;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(string $remarque): static
    {
        $this->remarque = $remarque;

        return $this;
    }
   
}

