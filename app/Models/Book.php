<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;
    
    protected $guarded = ['id', 'slug'];
    
     public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = $this->generateUniqueSlug($value);
    }

    private function generateUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $original = $slug;
        $count = 1;
        while (static::where('slug', $slug)->where('id', '!=', $this->id ?? 0)->exists()) {
            $slug = $original . '-' . $count++;
        }
        return $slug;
    }

    private function toCents($value): ?int
    {
        if (is_null($value)) return null;
        if (is_int($value)) return $value;
        return (int) round($value * 100);
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $this->toCents($value),
        );
    }

    protected function oldPrice(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value ? $value / 100 : null,
            set: fn ($value) => $this->toCents($value),
        );
    }

    public function authors(): BelongsToMany
    {
        return $this->belongsToMany(Author::class, 'author_book');
    }
    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class, 'book_genre');
    }
}
