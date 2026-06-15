<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Genre extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'slug'];
    
     public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
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

    public function books(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_genre');
    }
    
}
