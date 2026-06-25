<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

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
            'first_name.max' => trans('auth.profile.first_name.max'),
            'last_name.max'  => trans('auth.profile.last_name.max'),
            'nickname.max'   => trans('auth.profile.nickname.max'),
            'nickname.unique'=> trans('auth.profile.nickname.unique'),
            'email.email'    => trans('auth.profile.email.email'),
            'email.unique'   => trans('auth.profile.email.unique'),
            'password.min'   => trans('auth.profile.password.min'),
            'password.confirmed' => trans('auth.profile.password.confirmed'),
        ];
    }
}