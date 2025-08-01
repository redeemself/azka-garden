<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;
use App\Extensions\Database\ConnectionExtension;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Add compatibility for getName() method
        if (!method_exists(Connection::class, 'getName')) {
            $this->app->bind('db.connection', function ($app, $parameters) {
                return new ConnectionExtension(...$parameters);
            });
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
