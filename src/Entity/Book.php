<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $author = null;

    #[ORM\Column(length: 500)]
    private ?string $summary = null;

    #[ORM\Column(length: 255)]
    private ?string $genre = null;

    #[ORM\Column(type: 'integer')]
    private ?int $pages = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $coverImage = null;

    // One-to-many relationship with Review entity
    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Review::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    // --- Basic getters & setters ---

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): self
    {
        $this->author = $author;
        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $summary): self
    {
        $this->summary = $summary;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
        return $this;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setPages(int $pages): self
    {
        $this->pages = $pages;
        return $this;
    }

    public function getCoverImage(): ?string
    {
        return $this->coverImage;
    }

    public function setCoverImage(?string $coverImage): self
    {
        $this->coverImage = $coverImage;
        return $this;
    }

    // --- Relationships & computed fields ---

    /**
     * Returns all reviews associated with this book.
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    /**
     * Returns the average rating of the book based on reviews.
     */
    public function getAverageRating(): ?float
    {
        if ($this->reviews->isEmpty()) {
            return null; // No reviews yet
        }

        $totalRating = array_sum(array_map(fn($r) => $r->getRating(), $this->reviews->toArray()));
        return round($totalRating / $this->reviews->count(), 1);
    }

    /**
     * Returns the total number of reviews for this book.
     */
    public function getReviewCount(): int
    {
        return $this->reviews->count();
    }
}
