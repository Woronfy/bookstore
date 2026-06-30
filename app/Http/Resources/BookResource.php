<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'old_price' => $this->old_price,
            'year' => $this->year,
            'reviews_count' => $this->reviews_count ?? 0,
            'average_rating' => $this->reviews_avg_rating ? round($this->reviews_avg_rating, 1) : null,
            'cover' => $this->getFirstMediaUrl('images', 'thumb') ?: null,
            'images' => $this->getMedia('images')->map(fn ($media) => [
                'id' => $media->id,
                'url' => $media->getUrl(),
                'thumb' => $media->getUrl('thumb'),
            ]),
        ];
    }
}
