<?php

use App\Http\Controllers\Api\v1\FavoriteController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('favorites')->name('favorites.')->group(function () {
    Route::get('/', [FavoriteController::class, 'index'])->name('index');
    Route::post('/', [FavoriteController::class, 'store'])->name('store');
    Route::delete('/{book}', [FavoriteController::class, 'destroy'])->name('destroy');
    Route::delete('/', [FavoriteController::class, 'clear'])->name('clear');
});