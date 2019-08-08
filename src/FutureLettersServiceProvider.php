<?php

namespace Buzkall\FutureLetters;

use Buzkall\FutureLetters\Commands\SendFutureLetters;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\ServiceProvider;

class FutureLettersServiceProvider extends ServiceProvider
{
    use Notifiable;

    /**
     * Register services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function register()
    {
        $this->app->make('Buzkall\FutureLetters\FutureLetterController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function boot()
    {
        // load package routes after booting the app, so we can override the / route
        $this->app->booted(function () {
            $this->loadRoutesFrom(__DIR__ . '/routes.php');
        });

        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->app->make('Illuminate\Database\Eloquent\Factory')->load(__DIR__ . '/factories');

        $this->loadViewsFrom(__DIR__ . '/views', 'future-letters');
        // publish the views in case the user wants to override them
        $this->publishes([__DIR__ . '/views' => base_path('resources/views/buzkall/future-letters')]);

        if ($this->app->runningInConsole()) {
            $this->commands([SendFutureLetters::class]);
        }
    }
}
