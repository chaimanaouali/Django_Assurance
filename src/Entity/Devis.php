<?php

namespace App\Entity;

use App\Repository\DevisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: DevisRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'This email is already in use.')]

class Devis
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(type: "integer", name: "id_dev")]
    private $idDev;

    #[ORM\Column(type: "string", length: 255)]
    private $nom;

    #[ORM\Column(type: "string", length: 255)]
    private $prenom;

    #[ORM\Column(type: "string", length: 255)]
    private $adresse;
    #[Assert\NotBlank(message: 'Email cannot be blank')]
    #[Assert\Email(message: 'Invalid email address')]
    #[ORM\Column(type: "string", length: 255)]
    private $email;

    #[ORM\Column(type: "date", name: "date_naiss")]
    private $dateNaissance;

    #[ORM\Column(type: "integer", name: "num_tel")]
    #[Assert\NotBlank(message: 'Numéro de téléphone ne peut pas être vide')]
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'Le numéro de téléphone doit avoir au moins 8 chiffres',
        maxMessage: 'Le numéro de téléphone ne peut pas dépasser 8 chiffres'
    )]
    #[Assert\Regex(
        pattern: '/^\+?\d+$/',
        message: 'Le numéro de téléphone doit contenir uniquement des chiffres'
    )]
    private $numTel;

    #[ORM\Column(type: "string", length: 255)]
    private $modele;

    #[ORM\Column(type: "string", length: 255)]
    private $puissance;

    #[ORM\Column(type: "string", length: 255)]
    private $prix;

    public function getIdDev(): ?int
    {
        return $this->idDev;
    }

    public function setIdDev(int $idDev): self
    {
        $this->idDev = $idDev;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getDateNaissance(): ?\DateTimeInterface
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(\DateTimeInterface $dateNaissance): self
    {
        if ($dateNaissance instanceof \DateTimeInterface) {
            $this->dateNaissance = $dateNaissance;
        } elseif (is_string($dateNaissance)) {
            $this->dateNaissance = new \DateTime($dateNaissance);
        } else {
            throw new \InvalidArgumentException('La date de naissance doit être une instance de \DateTimeInterface ou une chaîne de caractères représentant une date valide.');
        }
    
        return $this;
    }

    public function getNumTel(): ?int
    {
        return $this->numTel;
    }

    public function setNumTel(int $numTel): self
    {
        $this->numTel = $numTel;
        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;
        return $this;
    }

    public function getPuissance(): ?string
    {
        return $this->puissance;
    }

    public function setPuissance(string $puissance): self
    {
        $this->puissance = $puissance;
        return $this;
    }

    public function getPrix(): ?string
    {
        return $this->prix;
    }

    public function setPrix(string $prix): self
    {
        $this->prix = $prix;
        return $this;
    }
}
