<?php

namespace App\Http\Requests\Api\v1;

use App\DTO\BookFilterDTO;
use Illuminate\Foundation\Http\FormRequest;

class BookFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page'        => ['sometimes', 'integer', 'min:1', 'max:100'],
            'sort_by'         => ['sometimes', 'string', 'in:price,created_at'],
            'sort_direction'  => ['sometimes', 'string', 'in:asc,desc'],
            'author_id'       => ['sometimes', 'integer', 'exists:authors,id'],
            'genre_id'        => ['sometimes', 'integer', 'exists:genres,id'],
            'year_from'       => ['sometimes', 'integer', 'min:1900', 'max:' . date('Y')],
            'year_to'         => ['sometimes', 'integer', 'min:1900', 'max:' . date('Y')],
        ];
    }

    public function toDTO(): BookFilterDTO
    {
        return new BookFilterDTO(
            sortBy: $this->validated('sort_by'),
            sortDirection: $this->validated('sort_direction', 'asc'),
            authorId: $this->validated('author_id') ? (int) $this->validated('author_id') : null,
            genreId: $this->validated('genre_id') ? (int) $this->validated('genre_id') : null,
            yearFrom: $this->validated('year_from') ? (int) $this->validated('year_from') : null,
            yearTo: $this->validated('year_to') ? (int) $this->validated('year_to') : null,
            perPage: $this->validated('per_page', 15),
        );
    }

    public function messages(): array
    {
        return [
            'sort_direction.in' => 'Направление сортировки должно быть asc или desc.',
            'author_id.exists' => 'Автор с таким ID не найден.',
            'genre_id.exists' => 'Жанр с таким ID не найден.',
            'year_from.min' => 'Год должен быть не менее 1900.',
            'year_to.max' => 'Год не может быть больше текущего.',
        ];
    }
}