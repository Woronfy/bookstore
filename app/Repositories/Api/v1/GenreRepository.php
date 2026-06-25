<?php

namespace App\Repositories\Api\v1;

use App\Models\Genre;
use Illuminate\Pagination\LengthAwarePaginator;

class GenreRepository
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Genre::withCount('books')->paginate($perPage);
    }
}