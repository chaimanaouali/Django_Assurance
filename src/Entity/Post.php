<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



#[ORM\Entity(repositoryClass: PostRepository::class)]
#[Vich\Uploadable]
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
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+(?: [a-zA-Z]+)* ?$/',
        message: 'The value {{ value }} is not a valid {{ type }}.'
    )]
    private string $description; // New attribute

    #[Vich\UploadableField(mapping: 'post_images', fileNameProperty: 'imageName')]
    #[Assert\NotBlank]
    private ?File $imageFile = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $imageName = null;


   

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(max:30)]
    private string $categorie ;


    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotNull()]
    #[Assert\NotBlank]
    #[Assert\LessThanOrEqual('today')]
    private \DateTimeImmutable $updatedAt;

    #[ORM\Column(type: 'integer')]
    private int $likeCount = 0; // New property for storing like count
    #[ORM\Column(type: 'boolean')]
private bool $signaled = false;
   

    




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

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
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

    
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
    public function getLikeCount(): int
    {
        return $this->likeCount;
    }

    public function setLikeCount(int $likeCount): self
    {
        $this->likeCount = $likeCount;

        return $this;
    }
    public function getSignaled(): bool
{
    return $this->signaled;
}

public function setSignaled(bool $signaled): self
{
    $this->signaled = $signaled;
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
    $restrictedWords = ['putin', 'merde', 'conard','gay']; // Define an array of restricted words
    
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
