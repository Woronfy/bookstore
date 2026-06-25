<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Builder;

interface QueryModifier
{
    public function apply(Builder $query): void;
}