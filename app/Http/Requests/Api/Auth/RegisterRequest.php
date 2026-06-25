<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'nickname'   => ['required', 'string', 'max:255', 'unique:authors,nickname'],
            'email'      => ['required', 'email', 'unique:authors,email'],
            'password'   => ['required', 'string', 'min:6'],
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => trans('auth.registration.first_name.required'),
            'last_name.required'  => trans('auth.registration.last_name.required'),
            'nickname.required'   => trans('auth.registration.nickname.required'),
            'nickname.unique'     => trans('auth.registration.nickname.unique'),
            'email.required'      => trans('auth.registration.email.required'),
            'email.email'         => trans('auth.registration.email.email'),
            'email.unique'        => trans('auth.registration.email.unique'),
            'password.required'   => trans('auth.registration.password.required'),
            'password.min'        => trans('auth.registration.password.min'),
        ];
    }
}