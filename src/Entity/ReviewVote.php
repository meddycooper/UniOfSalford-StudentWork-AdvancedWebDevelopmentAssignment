<?php

namespace App\Entity;

use App\Repository\ReviewVoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReviewVoteRepository::class)]
#[ORM\Table(name: 'review_vote', uniqueConstraints: [
    new ORM\UniqueConstraint(name: 'unique_user_review', columns: ['user_id', 'review_id'])
])]
class ReviewVote
{
    #[ORM\Id, ORM\GeneratedValue, ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Review::class, inversedBy: 'reviewVotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Review $review = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'reviewVotes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    // -------------------------
    // Getters and Setters
    // -------------------------

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReview(): ?Review
    {
        return $this->review;
    }

    public function setReview(?Review $review): static
    {
        $this->review = $review;
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
}
