<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title'=> $this->faker->sentence(3),
            'description'=> $this->faker->paragraph(2),
            'price'=> $this->faker->numberBetween(20000, 150000), 
            'old_price'=> $this->faker->optional(0.4)->numberBetween(20000, 150000),
            'year'=> $this->faker->numberBetween(1900,2026),
        ];
    }
}
