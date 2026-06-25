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
            'latest_book' => $this->whenLoaded('books', function () {
                $book = $this->books->first();
                return $book ? [
                    'id' => $book->id,
                    'title' => $book->title,
                ]:null;
            })
        ];
    }
}
