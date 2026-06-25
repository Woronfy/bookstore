<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyTwoFactorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'code'  => ['required', 'string', 'size:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => trans('auth.registration.email.required'),
            'email.email'    => trans('auth.registration.email.email'),
            'code.required'  => 'Код подтверждения обязателен.',
            'code.size'      => 'Код должен состоять из 4 символов.',
        ];
    }
}