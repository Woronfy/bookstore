<?php

namespace App\QueryModifiers;

use App\Contracts\QueryModifier;
use Illuminate\Database\Eloquent\Builder;

class FilterByGenre implements QueryModifier
{
    protected int $genreId;

    public function __construct(int $genreId)
    {
        $this->genreId = $genreId;
    }

    public function apply(Builder $query): void
    {
        $query->whereHas('genres', function (Builder $q) {
            $q->where('genre_id', $this->genreId);
        });
    }
}