<?php

namespace App\Entity;

use App\Repository\ConstatRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\UX\Turbo\Attribute\Broadcast;
use Symfony\Component\HttpFoundation\File\File;
use App\Entity\Assert\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConstatRepository::class)]
#[Broadcast]

class Constat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\NotBlank(message:"entrez date")]  
    #[ORM\Column(length: 255)]
    private ?\DateTime$date = null;

    
    #[Assert\NotBlank(message:"entrez lieu")]  
    #[Assert\NotEqualTo('aaa')]
    #[Assert\Length(max:20)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+(?: [a-zA-Z]+)* ?$/',
        message: 'The value {{ value }} is not a valid {{ type }}.'
    )]
    #[ORM\Column(length: 255)]
    private ?string $lieu = null;
   
  
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"entrez description")]  
    #[Assert\NotEqualTo('aaa')]
    #[Assert\Length(max:20)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+(?: [a-zA-Z]+)* ?$/',
        message: 'The value {{ value }} is not a valid {{ type }}.'
    )]
    private ?string $description = null;
    #[Assert\NotBlank(message:"entrez condition route")]  
    #[ORM\Column(length: 255)]
    private ?string $conditionroute = null;

    
    #[ORM\Column(length: 255)]
    private ?bool $rapportepolice = null;
    
    #[ORM\OneToOne(mappedBy: "identifiant", cascade: ['persist', 'remove'])]
    private ?Traitement $traitement = null;

  
    #[ORM\Column(length:255)]
    private ?string $photo = null;

    #[ORM\Column(length: 255)]
    private ?string $iduser = null;
    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDate(): ?\DateTime
    {
        return $this->date;
    }

    public function setDate(\DateTime $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): static
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getConditionroute(): ?string
    {
        return $this->conditionroute;
    }

    public function setConditionroute(string $conditionroute): static
    {
        $this->conditionroute = $conditionroute;

        return $this;
    }

    public function getRapportepolice(): ?bool
    {
        return $this->rapportepolice;
    }

    public function setRapportepolice(bool $rapportepolice): static
    {
        $this->rapportepolice = $rapportepolice;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }

    public function getIduser(): ?string
    {
        return $this->iduser;
    }

    public function setIduser(string $iduser): static
    {
        $this->iduser = $iduser;

        return $this;
    }
   
}