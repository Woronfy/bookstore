<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\Auth\LoginDTO;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function toDTO(): LoginDTO
    {
        return new LoginDTO(
            email: $this->validated('email'),
            password: $this->validated('password'),
        );
    }
}