<?php

namespace iProtek\Core;

use Illuminate\Support\ServiceProvider;

use iProtek\Core\Http\Kernel;

class iProtekServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register package services
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Bootstrap package services
        
        //$this->publishes([
        //    __DIR__.'/../database/migrations' => database_path('migrations'),
        //], 'migrations');

        
        /*
        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/iprotek'),
        ], 'public');
        */
        //Create link instead
        $target = __DIR__.'/../public';
        $link = public_path('iprotek');

        // Create symbolic link if it doesn't exist
        if (!file_exists($link)) {
            symlink($target, $link);
        }

        //Run this to publish
        //php artisan vendor:publish --tag=public --provider="iProtek\Core\iProtekServiceProvider"

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'iprotek_core');

        
        $this->app->extend('Illuminate\Contracts\Http\Kernel', function ($existingKernel, $app) {
            return new Kernel($app, $app['router']);
        });
    }
}