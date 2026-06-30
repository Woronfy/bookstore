<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use App\DTO\Auth\VerifyTwoFactorDTO;

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

    public function toDTO(): VerifyTwoFactorDTO
    {
        return new VerifyTwoFactorDTO(
            email: $this->validated('email'),
            code: $this->validated('code'),
        );
    }
}