<?php

namespace App\DTO\Review;

class VoteReviewDTO
{
    public function __construct(
        public readonly int $vote,
    ) {}
}