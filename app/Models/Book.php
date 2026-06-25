<?php

namespace App\Models;

use App\Casts\PriceCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Book extends Model
{
    use HasFactory, HasSlug;
    
    protected $guarded = ['id', 'slug'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug')
            ->doNotGenerateSlugsOnUpdate();
    }

    protected function casts(): array
    {
        return [
            'price' => PriceCast::class,
            'old_price' => PriceCast::class,
        ];
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'favorites', 'book_id', 'author_id')->withTimestamps();
    }
}
