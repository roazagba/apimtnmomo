<?php

namespace Roazagba\ApiMTNMomo;

use Illuminate\Support\ServiceProvider;
use Roazagba\ApiMTNMomo\Console\CreateApiUserCommand;

class MTNMoMoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/mtnmomo.php' => config_path('mtnmomo.php'),
        ], 'config');

        if ($this->app->runningInConsole()) {
            $this->commands([
                CreateApiUserCommand::class,
            ]);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/mtnmomo.php',
            'mtnmomo'
        );
    }
}
