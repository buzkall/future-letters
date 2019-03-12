<?php

namespace Buzkall\FutureLetters;

use Illuminate\Support\ServiceProvider;

class FutureLettersServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function register()
    {
        $this->app->make('Buzkall\FutureLetters\FutureLetterController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');

        $this->loadMigrationsFrom(__DIR__ . '/migrations');
        $this->app->make('Illuminate\Database\Eloquent\Factory')->load(__DIR__ . '/factories');

        $this->loadViewsFrom(__DIR__ . '/views', 'future-letters');
        $this->publishes([__DIR__ . '/views' => base_path('resources/views/buzkall/future-letters')]);
    }
}
