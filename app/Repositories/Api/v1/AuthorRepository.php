<?php

namespace App\Repositories\Api\v1;

use App\Models\Author;
use Illuminate\Pagination\LengthAwarePaginator;

class AuthorRepository
{
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Author::withCount('books')->with(['latestBook', 'media'])->paginate($perPage);
    }
}