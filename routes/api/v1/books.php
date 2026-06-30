<?php

use App\Http\Controllers\Api\v1\BookController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\ReviewController;

Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{id}', [BookController::class, 'show'])
    ->whereNumber('id')
    ->name('books.show');

Route::prefix('books/{book}')->group(function () {
    Route::get('/reviews', [ReviewController::class, 'index'])->name('books.reviews.index');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('books.reviews.store')->middleware('auth:sanctum');
    Route::get('/reviews/stats', [ReviewController::class, 'stats'])->name('books.reviews.stats');
});