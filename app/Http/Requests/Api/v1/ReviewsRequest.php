<?php

namespace App\Http\Requests\Api\v1;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\Review\ReviewFilterDTO;

class ReviewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'sort_by' => ['sometimes', 'string', 'in:created_at,rating'],
            'sort_direction' => ['sometimes', 'string', 'in:asc,desc'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }

    public function toDTO(): ReviewFilterDTO
    {
        return new ReviewFilterDTO(
            sortBy: $this->validated('sort_by', 'created_at'),
            sortDirection: $this->validated('sort_direction', 'desc'),
            perPage: (int) $this->validated('per_page', 15),
        );
    }
}