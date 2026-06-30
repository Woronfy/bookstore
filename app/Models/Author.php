<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $nickname
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Book> $books
 * @property-read Book|null $latestBook
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Book> $favoriteBooks
 *
 * @method static \Database\Factories\AuthorFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static> query()
 */

class Author extends Model implements HasMedia
{
    use HasFactory, HasApiTokens, InteractsWithMedia;

    protected $guarded = ['id'];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'author_book');
    }

     public function latestBook(): HasOne
    {
        return $this->hasOne(Book::class)->latestOfMany('year');
    }

    public function favoriteBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'favorites', 'author_id', 'book_id')->withTimestamps();
    }
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }
    public function reviewVotes(): HasMany
    {
        return $this->hasMany(ReviewVote::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
             ->singleFile()
             ->useFallbackUrl('/images/default-avatar.png')
             ->useFallbackPath(public_path('/images/default-avatar.png'));
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(150)
             ->height(150)
             ->sharpen(10)
             ->performOnCollections('avatar');
    }
}
