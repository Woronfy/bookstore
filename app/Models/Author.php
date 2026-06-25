<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;

class Author extends Model
{
    use HasFactory, HasApiTokens;

    protected $guarded = ['id'];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'author_book');
    }

    public function favoriteBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'favorites', 'author_id', 'book_id')->withTimestamps();
    }
}
