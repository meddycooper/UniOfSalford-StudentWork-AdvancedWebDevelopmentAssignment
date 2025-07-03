<?php

namespace App\Model;

class BookSearchCriteria
{
    public ?string $title = null;
    public ?string $author = null;
    public ?string $genre = null;
    public ?float $rating = null;
    public ?int $minPages = null;
    public ?int $maxPages = null;
}