<?php

namespace App\Entity;

use App\Entity\Book;
use App\Entity\User;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
class Favorite
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'favorites')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(targetEntity: Book::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    // -------------------------
    // Getters and Setters
    // -------------------------

    /**
     * Get the ID of the favorite
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the user who favorited the book
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * Set the user who favorited the book
     */
    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get the book that was favorited
     */
    public function getBook(): ?Book
    {
        return $this->book;
    }

    /**
     * Set the book that was favorited
     */
    public function setBook(?Book $book): static
    {
        $this->book = $book;
        return $this;
    }
}
