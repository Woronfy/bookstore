<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')
    ->name('api.v1.')
    ->group(static function (): void {
        require base_path('routes/api/v1/auth.php');
        require base_path('routes/api/v1/books.php');
        require base_path('routes/api/v1/authors.php');
        require base_path('routes/api/v1/genres.php');
        require base_path('routes/api/v1/favorites.php');
    });