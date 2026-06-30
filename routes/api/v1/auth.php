<?php

use App\Http\Controllers\Api\v1\AuthController;
use Illuminate\Support\Facades\Route;

Route::controller(AuthController::class)->group(static function (): void {
    Route::post('/register', 'register')->name('register');
    Route::post('/login', 'sendCode')->name('login');
    Route::post('/verify-2fa', 'verifyTwoFactor')->name('verify-2fa');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', 'logout')->name('logout');
        Route::get('/profile', 'profile')->name('profile');
        Route::patch('/profile', 'updateProfile')->name('profile.update');
    });
});