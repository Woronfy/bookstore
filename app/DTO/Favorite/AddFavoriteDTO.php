<?php

namespace App\DTO\Favorite;

class AddFavoriteDTO
{
    public function __construct(
        public readonly int $bookId,
    ) {}
}