<?php

namespace App\Services;

use App\DTO\Auth\RegisterDTO;
use App\DTO\Auth\UpdateProfileDTO;
use App\Models\Author;
use Illuminate\Support\Facades\Hash;

class AuthService
{

    public function register(RegisterDTO $dto): Author
    {
        return Author::create([
            'first_name' => $dto->firstName,
            'last_name'  => $dto->lastName,
            'nickname'   => $dto->nickname,
            'email'      => $dto->email,
            'password'   => $dto->password, 
        ]);
    }

    public function getAuthorIfCredentialsValid(string $email, string $password): ?Author
    {
        $author = Author::where('email', $email)->first();

        if (!$author || !Hash::check($password, $author->password)) {
            return null;
        }

        return $author;
    }

    public function updateProfile(Author $author, UpdateProfileDTO $dto): Author
    {
        $author->update($dto->toFilteredArray());
        return $author;
    }
}