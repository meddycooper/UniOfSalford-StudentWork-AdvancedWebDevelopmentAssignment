<?php

namespace App\Repository;

use App\Model\Review;

/**
 * A safe-to-share repository skeleton for managing reviews.
 * No connection to a private database or sensitive entities.
 */
class ReviewRepository
{
    /**
     * Simulates fetching all reviews.
     *
     * @return Review[]
     */
    public function findAll(): array
    {
        // Return an empty array or mock data
        return [];
    }

    /**
     * Example method for filtering reviews.
     *
     * @return Review[]
     */
    public function findByExampleField($value): array
    {
        // Pseudo-logic for public sharing
        return [];
    }

    /**
     * Example method for fetching a single review.
     *
     * @return Review|null
     */
    public function findOneBySomeField($value): ?Review
    {
        // Pseudo-logic for public sharing
        return null;
    }
}
