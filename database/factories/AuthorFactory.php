<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\Testing\FileFactory;

/**
 * @extends Factory<Author>
 */
class AuthorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'nickname' => $this->faker->unique()->userName(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password')
        ];
    }

    public function withAvatar(): static
    {
        return $this->afterCreating(function (Author $author) {
            $fileFactory = new FileFactory();

            $image = $fileFactory->image(
                width: 400,
                height: 400,
                category: 'avatars'
            );

            $author->addMedia($image)
                   ->usingName('avatar')
                   ->usingFileName("avatar_{$author->id}.jpg")
                   ->toMediaCollection('avatar');
        });
    }
}
