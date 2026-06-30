<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'book_id' => \App\Models\Book::factory(),
            'author_id' => \App\Models\Author::factory(),
            'rating' => $this->faker->numberBetween(1, 5),
            'comment' => $this->faker->paragraph,
            'pros' => $this->faker->optional()->sentence,
            'cons' => $this->faker->optional()->sentence,
            'is_anonymous' => $this->faker->boolean,
        ];
    }
}