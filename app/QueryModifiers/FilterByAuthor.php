<?php

namespace App\QueryModifiers;

use App\Contracts\QueryModifier;
use Illuminate\Database\Eloquent\Builder;

class FilterByAuthor implements QueryModifier
{
    protected int $authorId;

    public function __construct(int $authorId)
    {
        $this->authorId = $authorId;
    }

    public function apply(Builder $query): void
    {
        $query->whereHas('authors', function (Builder $q) {
            $q->where('author_id', $this->authorId);
        });
    }
}