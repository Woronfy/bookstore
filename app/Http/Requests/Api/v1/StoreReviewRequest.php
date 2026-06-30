<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\Review\StoreReviewDTO;

class StoreReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
            'pros' => ['nullable', 'string', 'max:500'],
            'cons' => ['nullable', 'string', 'max:500'],
            'is_anonymous' => ['sometimes', 'boolean'],
        ];
    }

    public function toDTO(): StoreReviewDTO
    {
        return new StoreReviewDTO(
            bookId: (int) $this->route('book'),
            rating: (int) $this->validated('rating'),
            comment: $this->validated('comment'),
            pros: $this->validated('pros'),
            cons: $this->validated('cons'),
            isAnonymous: $this->validated('is_anonymous', false),
        );
    }
}