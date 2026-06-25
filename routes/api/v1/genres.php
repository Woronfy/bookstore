<?php

use App\Http\Controllers\Api\v1\GenreController;
use Illuminate\Support\Facades\Route;

Route::get('/genres', [GenreController::class, 'index'])->name('genres.index');