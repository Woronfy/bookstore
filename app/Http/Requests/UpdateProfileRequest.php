<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use App\DTO\Auth\UpdateProfileDTO;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $author = $this->user();
        $authorId = $author->id;

        return [
            'first_name' => ['sometimes', 'string', 'max:255'],
            'last_name'  => ['sometimes', 'string', 'max:255'],
            'nickname'   => ['sometimes', 'string', 'max:255', Rule::unique('authors')->ignore($authorId)],
            'email'      => ['sometimes', 'email', Rule::unique('authors')->ignore($authorId)],
            'password'   => ['sometimes', 'string', 'min:6', 'confirmed'],
        ];
    }

    public function messages(): array
    {
        return [
            'nickname.unique'=> trans('auth.profile.nickname.unique'),
            'email.unique'   => trans('auth.profile.email.unique'),
        ];
    }

    public function toDTO(): UpdateProfileDTO
    {
        return new UpdateProfileDTO(
            firstName: $this->validated('first_name'),
            lastName: $this->validated('last_name'),
            nickname: $this->validated('nickname'),
            email: $this->validated('email'),
            password: $this->validated('password'),
        );
    }
}