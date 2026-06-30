<?php

namespace App\DTO\Auth;

class LoginDTO
{
    public function __construct(
        public readonly string $email,
        public readonly string $password,
    ) {}
}