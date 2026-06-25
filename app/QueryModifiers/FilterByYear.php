<?php

namespace App\QueryModifiers;

use App\Contracts\QueryModifier;
use Illuminate\Database\Eloquent\Builder;

class FilterByYearRange implements QueryModifier
{
    protected ?int $from;
    protected ?int $to;

    public function __construct(?int $from = null, ?int $to = null)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function apply(Builder $query): void
    {
        if ($this->from) {
            $query->where('year', '>=', $this->from);
        }
        if ($this->to) {
            $query->where('year', '<=', $this->to);
        }
    }
}