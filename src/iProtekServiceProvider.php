<?php

namespace iProtek\Core;

use Illuminate\Support\ServiceProvider;

use iProtek\Core\Http\Kernel;
use iProtek\Core\Helpers\PayHttp;
use Illuminate\Support\Facades\Gate;
use iProtek\Core\Console\Commands\FileImportBatchCommand;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Schema\Blueprint;

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

        //DEFAULT SETTINGS    
        Blueprint::macro('iprotekDefaultColumns', function () {
            $this->bigIncrements('id');
            $this->bigInteger('group_id')->nullable();
            $this->bigInteger('pay_created_by')->nullable(); 
            $this->bigInteger('pay_updated_by')->nullable();
            $this->bigInteger('pay_deleted_by')->nullable();
            $this->bigInteger('branch_id')->nullable();
            $this->softDeletes();
            $this->timestamps();
        });

        //COMMANDS REGISTRATIONS PREPARATIONS 
        if ($this->app->runningInConsole()) {
            $this->commands([
                FileImportBatchCommand::class,
            ]);
        }

        $fnCheckSuperAdmin = function ($user) {

            //1st PRIORITY IS THE APP ACCOUNT ID
            //Log::info('Checking super admin: '.config('iprotek.sa_app_account_id').' == '.PayHttp::pay_account_id($user));
            if(config('iprotek.sa_app_account_id')){
                return config('iprotek.sa_app_account_id') == PayHttp::pay_account_id($user);
            }
            
            //2nd PRIORITY IS THE USERADMIN ID
            //Log::info('Checking super admin: '.config('iprotek.sa_user_admin_id').' == '. $user->id);
            if(config('iprotek.sa_user_admin_id')){
                return config('iprotek.sa_user_admin_id') ==  $user->id;
            }

            return $user->id == 1;
        };

        // Bootstrap package services
        //SUPERADMIN SETUP
        Gate::define('super_admin', $fnCheckSuperAdmin);
        Gate::define('super-admin', $fnCheckSuperAdmin);
        Gate::define('superadmin', $fnCheckSuperAdmin);




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