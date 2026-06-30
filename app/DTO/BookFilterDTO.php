<?php

namespace App\DTO;

class BookFilterDTO
{
    public function __construct(
        public readonly ?string $sortBy = null,
        public readonly string $sortDirection = 'asc',
        public readonly ?int $authorId = null,
        public readonly ?int $genreId = null,
        public readonly ?int $yearFrom = null,
        public readonly ?int $yearTo = null,
        public readonly int $perPage = 15,
    ) {}
}