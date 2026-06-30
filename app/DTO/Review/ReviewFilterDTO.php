<?php

namespace App\DTO\Review;

class ReviewFilterDTO
{
    public function __construct(
        public readonly ?string $sortBy = 'created_at',
        public readonly string $sortDirection = 'desc',
        public readonly int $perPage = 15,
    ) {}
}