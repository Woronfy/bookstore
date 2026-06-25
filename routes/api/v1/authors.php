<?php

use App\Http\Controllers\Api\v1\AuthorController;
use Illuminate\Support\Facades\Route;

Route::get('/authors', [AuthorController::class, 'index'])->name('authors.index');