<?php

namespace App\DTO\Auth;

class VerifyTwoFactorDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $code,
    ) {}
}