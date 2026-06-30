<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'pros' => $this->pros,
            'cons' => $this->cons,
            'is_anonymous' => $this->is_anonymous,
            'author' => $this->when(
                !$this->is_anonymous && $this->relationLoaded('author'),
                fn () => [
                    'id' => $this->author->id,
                    'nickname' => $this->author->nickname,
                    'avatar' => $this->author->getFirstMediaUrl('avatar', 'thumb') ?: null,
                ]
            ),
            'votes' => [
                'likes' => $this->likes_count ?? 0,
                'dislikes' => $this->dislikes_count ?? 0,
                'rating' => $this->votes_sum_vote ?? 0,
                'user_vote' => $this->when(auth()->check(), function () {
                    return $this->userVote ? $this->userVote->vote : null;
                }),
            ],
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}