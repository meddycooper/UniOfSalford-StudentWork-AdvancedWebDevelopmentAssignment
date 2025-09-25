<?php

namespace App\Entity;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\Comment;
use App\Entity\ReviewVote;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $reviewText = null;

    #[ORM\Column]
    #[Groups(['review:read'])]
    private ?int $rating = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\Column(type: 'integer')]
    private int $upvotes = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $flagged = false;

    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'review', targetEntity: Comment::class, cascade: ['remove'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'review', targetEntity: ReviewVote::class)]
    private Collection $reviewVotes;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->reviewVotes = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    // -------------------------
    // Getters and Setters
    // -------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReviewText(): ?string
    {
        return $this->reviewText;
    }

    public function setReviewText(string $reviewText): static
    {
        $this->reviewText = $reviewText;
        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }

    public function setBook(?Book $book): static
    {
        $this->book = $book;
        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setReview($this);
        }
        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment) && $comment->getReview() === $this) {
            $comment->setReview(null);
        }
        return $this;
    }

    public function getUpvotes(): int
    {
        return $this->upvotes;
    }

    public function setUpvotes(int $upvotes): static
    {
        $this->upvotes = $upvotes;
        return $this;
    }

    public function incrementUpvotes(): static
    {
        $this->upvotes++;
        return $this;
    }

    /**
     * @return Collection<int, ReviewVote>
     */
    public function getReviewVotes(): Collection
    {
        return $this->reviewVotes;
    }

    public function addReviewVote(ReviewVote $vote): static
    {
        if (!$this->reviewVotes->contains($vote)) {
            $this->reviewVotes->add($vote);
            $vote->setReview($this);
        }
        return $this;
    }

    public function removeReviewVote(ReviewVote $vote): static
    {
        if ($this->reviewVotes->removeElement($vote) && $vote->getReview() === $this) {
            $vote->setReview(null);
        }
        return $this;
    }

    public function isFlagged(): bool
    {
        return $this->flagged;
    }

    public function setFlagged(bool $flagged): static
    {
        $this->flagged = $flagged;
        return $this;
    }

    public function getUpvotesCount(): int
    {
        return $this->reviewVotes->count();
    }
}
