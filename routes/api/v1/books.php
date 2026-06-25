<?php

use App\Http\Controllers\Api\v1\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])
    ->whereNumber('id')
    ->name('books.show');