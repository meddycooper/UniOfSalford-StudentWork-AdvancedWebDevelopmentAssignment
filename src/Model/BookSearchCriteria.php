<?php

namespace App\Model;

/**
 * A simple model to hold book search criteria.
 * Safe for public sharing – no sensitive links to your project data.
 */
class BookSearchCriteria
{
    public ?string $title = null;
    public ?string $author = null;
    public ?string $genre = null;
    public ?float $rating = null;
    public ?int $minPages = null;
    public ?int $maxPages = null;
}
