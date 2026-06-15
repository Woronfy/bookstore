<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Notification\LogNotifier;
use App\Services\Notification\NotifierInterface;

class AppServiceProvider extends ServiceProvider

{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(NotifierInterface::class, function () {
            return new LogNotifier();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
