<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[ORM\Entity(repositoryClass: CommentaireRepository::class)]
class Commentaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+(?: [a-zA-Z]+)* ?$/',
        message: 'The value {{ value }} is not a valid {{ type }}.'
    )]
    private string $contenu;

    #[ORM\Column(type: 'datetime')]
    #[Assert\LessThanOrEqual('today')]
    private \DateTimeInterface $dateCreation;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: "Le nom de l'auteur doit contenir au moins {{ limit }} caractères",
        maxMessage: "Le nom de l'auteur ne peut pas dépasser {{ limit }} caractères"
    )]
    #[Assert\Regex(
        pattern: '/^[a-zA-Z]+(?: [a-zA-Z]+)* ?$/',
        message: 'The value {{ value }} is not a valid {{ type }}.'
    )]
    private string $auteur;

    #[ORM\ManyToOne(targetEntity: Post::class)]
    #[ORM\JoinColumn(name: "post_id", referencedColumnName: "id")]
    private Post $post;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): void
    {
        $this->contenu = $contenu;
    }

    public function getDateCreation(): \DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    public function getAuteur(): string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): void
    {
        $this->auteur = $auteur;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): self
    {
        $this->post = $post;
        return $this;
    }

    
   /**
 * @Assert\Callback
 */
public function validateWord(ExecutionContextInterface $context)
{
    $restrictedWords = ['jassem', 'sandid', 'iath']; // Define an array of restricted words
    
    foreach ($restrictedWords as $restrictedWord) {
        
       
        if (stripos($this->getContenu(), $restrictedWord) !== false) {
            $context->buildViolation('The titre contains a restricted word: '.$restrictedWord)
                ->atPath('contenu')
                ->addViolation();
        }
        
        // If you want to stop checking once a restricted word is found, you can break out of the loop here.
        // break;
    }
}
    
}
