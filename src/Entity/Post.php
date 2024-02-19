<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\NotEqualTo('aaa')]
    #[Assert\Length(max:30)]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+(?: [a-zA-Z]+)* ?$/',
        message: 'The value {{ value }} is not a valid {{ type }}.'
    )]
    private string $titre;
    
    
    

    #[ORM\Column(length: 255)]
    #[Assert\Length(min: 10, max: 255)] // Update the length according to your needs
    #[Assert\NotBlank]
    private string $description; // New attribute

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Image(
        minWidth: 200,
        maxWidth: 500,
        minHeight: 200,
        maxHeight: 500,
    )]
    #[Assert\Image(
        mimeTypesMessage: 'Please upload a valid image file.'
    )]
    private string $image;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual('today')]
    private \DateTimeInterface $dateCreation;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(max:30)]
    private string $categorie ;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 15)]
    #[Assert\NotBlank]
   

    private string $status ;

    public function __construct()
    {
        // Initialize the categorie property with an empty string or any default value
        $this->categorie = '';
        $this->status = '';

    }
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(string $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function __toString(): string
    {
        return $this->getTitre(); // Assuming you want to use the titre property as the string representation
    }

   /**
 * @Assert\Callback
 */
public function validateWord(ExecutionContextInterface $context)
{
    $restrictedWords = ['jassem', 'sandid', 'iath']; // Define an array of restricted words
    
    foreach ($restrictedWords as $restrictedWord) {
        
        if (stripos($this->titre, $restrictedWord) !== false) {
            $context->buildViolation('The titre contains a restricted word: '.$restrictedWord)
                ->atPath('titre')
                ->addViolation();
        }
        if (stripos($this->description, $restrictedWord) !== false) {
            $context->buildViolation('The titre contains a restricted word: '.$restrictedWord)
                ->atPath('description')
                ->addViolation();
        }
        
        // If you want to stop checking once a restricted word is found, you can break out of the loop here.
        // break;
    }
}
    
      
}
