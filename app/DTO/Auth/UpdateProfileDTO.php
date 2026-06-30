<?php

namespace App\DTO\Auth;

class UpdateProfileDTO
{
    public function __construct(
        public readonly ?string $firstName = null,
        public readonly ?string $lastName = null,
        public readonly ?string $nickname = null,
        public readonly ?string $email = null,
        public readonly ?string $password = null,
    ) {}

    public function toFilteredArray(): array
    {
        $data = [];

        if ($this->firstName !== null) {
            $data['first_name'] = $this->firstName;
        }
        if ($this->lastName !== null) {
            $data['last_name'] = $this->lastName;
        }
        if ($this->nickname !== null) {
            $data['nickname'] = $this->nickname;
        }
        if ($this->email !== null) {
            $data['email'] = $this->email;
        }
        if ($this->password !== null) {
            $data['password'] = $this->password;
        }

        return $data;
    }
}