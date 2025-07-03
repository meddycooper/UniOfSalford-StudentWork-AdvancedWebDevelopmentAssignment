<?php
namespace App\Entity;

use App\Entity\Book;
use App\Entity\User;
use App\Entity\ReviewVote;
use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;


#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $review_text = null;

    #[ORM\Column]
    #[Groups(['review:read'])]
    private ?int $rating = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: 'integer')]
    private ?int $upvotes = 0;

    #[ORM\Column(type: 'boolean')]
    private bool $flagged = false;

    // Many-to-one relationship with Book
    #[ORM\ManyToOne(targetEntity: Book::class, inversedBy: 'reviews')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Book $book = null;

    // Many-to-one relationship with User
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviews')] // <-- Added inversedBy
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'review', targetEntity: Comment::class, cascade: ['remove'])]
    private Collection $comments;

    /**
     * @var Collection<int, ReviewVote>
     */
    #[ORM\OneToMany(targetEntity: ReviewVote::class, mappedBy: 'review')]
    private Collection $reviewVotes;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->reviewVotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }
    public function getReviewText(): ?string
    {
        return $this->review_text;
    }

    public function setReviewText(string $review_text): static
    {
        $this->review_text = $review_text;
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
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;
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
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setReview($this);
        }
        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getReview() === $this) {
                $comment->setReview(null);
            }
        }
        return $this;
    }
    public function getUpvotesCount(): int
    {
        return $this->reviewVotes->count();
    }
    public function getUpvotes(): int
    {
        return $this->upvotes;
    }
    public function setUpvotes(int $upvotes): self
    {
        $this->upvotes = $upvotes;
        return $this;
    }
    public function incrementUpvotes(): self
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

    public function addReviewVote(ReviewVote $reviewVote): static
    {
        if (!$this->reviewVotes->contains($reviewVote)) {
            $this->reviewVotes->add($reviewVote);
            $reviewVote->setReview($this);
        }

        return $this;
    }

    public function removeReviewVote(ReviewVote $reviewVote): static
    {
        if ($this->reviewVotes->removeElement($reviewVote)) {
            // set the owning side to null (unless already changed)
            if ($reviewVote->getReview() === $this) {
                $reviewVote->setReview(null);
            }
        }

        return $this;
    }
    public function getFlagged(): bool
    {
        return $this->flagged;
    }

    public function setFlagged(bool $flagged): self
    {
        $this->flagged = $flagged;

        return $this;
    }
}
