<?php

namespace App\Repositories\Api\v1;

use App\Models\Book;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class BookRepository
{
    public function getQuery(): Builder
    {
        return Book::query();
    }
    
    public function getPaginated(int $perPage = 15): LengthAwarePaginator
    {
        return Book::paginate($perPage);
    }
}