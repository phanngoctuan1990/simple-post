<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Logging after send Mail
        \Queue::after(function (JobProcessed $event) {
            \Log::info("Queue completed" . ($event->connectionName) . '/n' . json_encode($event->data));
        });

        \Queue::before(function (JobProcessing $event) {
            \Log::info("Connection name:" . $event->connectionName);
        });
    }
}
