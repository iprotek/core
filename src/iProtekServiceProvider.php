<?php

namespace iProtek\Core;

use Illuminate\Support\ServiceProvider;

use iProtek\Core\Http\Kernel;
use iProtek\Core\Helpers\PayHttp;
use Illuminate\Support\Facades\Gate;

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
        //SUPERADMIN SETUP
        Gate::define('super_admin', function ($user) {
            return $user->can('superadmin');
        });
        Gate::define('super-admin', function ($user) {
            return $user->can('superadmin');
        });
        Gate::define('superadmin', function ($user) {

            //1st PRIORITY IS THE APP ACCOUNT ID
            //iprotek.sa_app_account_id
            if(config('iprotek.sa_app_account_id')){
                return config('iprotek.sa_app_account_id') == PayHttp::pay_account_id($user);
            }
            
            //2nd PRIORITY IS THE USERADMIN ID
            //iprotek.sa_user_admin_id
            if(config('iprotek.sa_user_admin_id')){
                return config('iprotek.sa_user_admin_id') ==  $user->id;
            }

            return $user->id == 1;
        });




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
        //$this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'iprotek_core');

        
        $this->app->extend('Illuminate\Contracts\Http\Kernel', function ($existingKernel, $app) {
            return new Kernel($app, $app['router']);
        });


        $this->mergeConfigFrom(
            __DIR__ . '/../config/iprotek.php', 'iprotek'
        );

        /*
        $this->app->extend('Illuminate\Contracts\Http\Kernel', function ($existingKernel, $app) {
            return new Kernel($app, $app['router']);
        });
        */
    }
}