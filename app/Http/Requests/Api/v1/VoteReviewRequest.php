<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\Review\VoteReviewDTO;

class VoteReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'vote_type' => ['required', 'string', 'in:like,dislike'],
        ];
    }

    public function toDTO(): VoteReviewDTO
    {
        $vote = $this->validated('vote_type') === 'like' ? 1 : -1;
        return new VoteReviewDTO($vote);
    }
}