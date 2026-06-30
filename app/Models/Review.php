<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $book_id
 * @property int $author_id
 * @property int $rating
 * @property string $comment
 * @property string|null $pros
 * @property string|null $cons
 * @property bool $is_anonymous
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @property-read Book $book
 * @property-read Author $author
 */

class Review extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_anonymous' => 'boolean',
        'rating' => 'integer',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(ReviewVote::class);
    }

    public function userVote(): HasOne
    {
        return $this->hasOne(ReviewVote::class)
            ->where('author_id', auth()->id())
            ->withDefault(['vote' => null]);
    }
}
