<?php

namespace App\DTO\Auth;

class RegisterDTO
{
    public function __construct(
        public readonly string $firstName,
        public readonly string $lastName,
        public readonly string $nickname,
        public readonly string $email,
        public readonly string $password,
    ) {}
}