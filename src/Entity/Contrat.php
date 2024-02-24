<?php

namespace App\Entity;

use App\Repository\ContratRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $dateDebutContrat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank]
    private ?\DateTimeInterface $datefinContrat = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $adresseAssur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 8,
        max: 8,
        exactMessage: 'Phone number should be exactly {{ limit }} characters long.'
    )]
    private ?string $numeroAssur = null;

    #[ORM\OneToOne(targetEntity: Type::class, cascade: ['persist', 'remove'])]
    private ?Type $type_couverture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebutContrat(): ?\DateTimeInterface
    {
        return $this->dateDebutContrat;
    }

    public function setDateDebutContrat(\DateTimeInterface $dateDebutContrat): static
    {
        $this->dateDebutContrat = $dateDebutContrat;

        return $this;
    }

    public function getDatefinContrat(): ?\DateTimeInterface
    {
        return $this->datefinContrat;
    }

    public function setDatefinContrat(\DateTimeInterface $datefinContrat): static
    {
        $this->datefinContrat = $datefinContrat;

        return $this;
    }

    public function getAdresseAssur(): ?string
    {
        return $this->adresseAssur;
    }

    public function setAdresseAssur(string $adresseAssur): static
    {
        $this->adresseAssur = $adresseAssur;

        return $this;
    }

    public function getNumeroAssur(): ?string
    {
        return $this->numeroAssur;
    }

    public function setNumeroAssur(string $numeroAssur): static
    {
        $this->numeroAssur = $numeroAssur;

        return $this;
    }

    public function getTypeCouverture(): ?Type
    {
        return $this->type_couverture;
    }

    public function setTypeCouverture(?Type $type_couverture): self
    {
        $this->type_couverture = $type_couverture;

        return $this;
    }

    #[Assert\Callback(callback: 'validateNumeroAssur')]
    public function validateNumeroAssur(ExecutionContextInterface $context, $payload)
    {
        if ($this->numeroAssur !== null) {
            // Check if the length is exactly 8
            if (strlen($this->numeroAssur) !== 8) {
                $context->buildViolation('The length of numeroAssur should be exactly 8 characters.')
                    ->atPath('numeroAssur')
                    ->addViolation();
            }

            // Check if the first digit is 2, 5, or 9
            $firstDigit = substr($this->numeroAssur, 0, 1);
            if (!in_array($firstDigit, ['2', '5', '9'])) {
                $context->buildViolation('The first digit of numeroAssur should be 2, 5, or 9.')
                    ->atPath('numeroAssur')
                    ->addViolation();
            }
        }
    }

    #[Assert\Callback(callback: 'validateDateOrder')]
    public function validateDateOrder(ExecutionContextInterface $context, $payload)
    {
        if ($this->dateDebutContrat !== null && $this->datefinContrat !== null) {
            // Check if dateDebutContrat is after datefinContrat
            if ($this->dateDebutContrat >= $this->datefinContrat) {
                $context->buildViolation('The start date must be before the end date.')
                    ->atPath('dateDebutContrat')
                    ->addViolation();
            }
        }
    }
}
