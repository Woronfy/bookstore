<?php

namespace App\Http\Requests\Api\Auth;


use Illuminate\Foundation\Http\FormRequest;
use App\DTO\Auth\RegisterDTO;

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

    public function toDTO(): RegisterDTO
    {
        return new RegisterDTO(
            firstName: $this->validated('first_name'),
            lastName: $this->validated('last_name'),
            nickname: $this->validated('nickname'),
            email: $this->validated('email'),
            password: $this->validated('password'),
        );
    }
}