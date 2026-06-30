<?php

namespace App\DTO\Review;

class StoreReviewDTO
{
    public function __construct(
        public readonly int $bookId,
        public readonly int $rating,
        public readonly string $comment,
        public readonly ?string $pros,
        public readonly ?string $cons,
        public readonly bool $isAnonymous,
    ) {}
}