<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'nickname' => $this->nickname,
            'books_count' => $this->books_count,
            'avatar' => $this->getFirstMediaUrl('avatar', 'thumb') ?: null,
            'latest_book' => $this->whenLoaded('latestBook', function () {
                return [
                    'id' => $this->latestBook->id,
                    'title' => $this->latestBook->title,
                    'year' => $this->latestBook->year,
                ];
            }),
        ];
    }
}
