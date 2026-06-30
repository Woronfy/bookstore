<?php

namespace App\QueryModifiers;

use App\Contracts\QueryModifier;
use Illuminate\Database\Eloquent\Builder;

class SortByPrice implements QueryModifier
{
    protected string $direction;

    public function __construct(string $direction = 'asc')
    {
        $this->direction = in_array($direction, ['asc', 'desc']) ? $direction : 'asc';
    }

    public function apply(Builder $query): void
    {
        $query->orderBy('price', $this->direction);
    }
}