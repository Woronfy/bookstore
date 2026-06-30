<?php

namespace App\Models;

use App\Casts\PriceCast;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property float $price
 * @property float|null $old_price
 * @property int $year
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Author> $authors
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Genre> $genres
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Author> $favoritedBy
 *
 * @method static \Database\Factories\BookFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static> query()
 */

class Book extends Model implements HasMedia
{
    use HasFactory, HasSlug, InteractsWithMedia;
    
    protected $guarded = ['id'];

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
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

     public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
             ->useFallbackUrl('/images/no-image.png')
             ->useFallbackPath(public_path('/images/no-image.png'));
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
             ->width(300)
             ->height(300)
             ->sharpen(10)
             ->performOnCollections('images');
    }

}
