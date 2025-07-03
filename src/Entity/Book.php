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

    // One-to-many relationship with Review
    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Review::class)]
    private Collection $reviews;

    public function __construct()
    {
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;
        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->author;
    }

    public function setAuthor(string $author): static
    {
        $this->author = $author;
        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getPages(): ?int
    {
        return $this->pages;
    }

    public function setSummary(string $summary): static
    {
        $this->summary = $summary;
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): static
    {
        $this->genre = $genre;
        return $this;
    }

    // One-to-many getter for reviews
    public function getReviews(): Collection
    {
        return $this->reviews;
    }
    public function getAverageRating(): ?float
    {
        $reviews = $this->getReviews();
        if ($reviews->isEmpty()) {
            return null; // No reviews yet
        }

        $totalRating = 0;
        foreach ($reviews as $review) {
            $totalRating += $review->getRating();
        }

        return round($totalRating / count($reviews), 1); // Average rating rounded to 1 decimal place
    }

    public function setPages(int $pages): static
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
    public function getReviewCount(): int
    {
        return $this->reviews->count();
    }
}
