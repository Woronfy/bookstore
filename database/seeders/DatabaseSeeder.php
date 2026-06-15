<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Author;
use App\Models\Book;
use App\Models\Genre;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory(rand(20, 30))->create();

        Genre::factory(20)->create();

        $authors = Author::factory(20)->create();



        $authors->each(function ($author) {
            $numBooks = rand(3, 7);
            $books = Book::factory($numBooks)->create();

           foreach ($books as $book) {
                $randomAuthors = Author::inRandomOrder()->take(rand(1, 3))->pluck('id');
                $book->authors()->attach($randomAuthors);

                $randomGenres = Genre::inRandomOrder()->take(rand(1, 3))->pluck('id');
                $book->genres()->attach($randomGenres);
            }
        });
    }
}
