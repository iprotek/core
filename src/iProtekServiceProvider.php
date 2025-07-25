<?php

namespace iProtek\Core;

use Illuminate\Support\ServiceProvider;

use iProtek\Core\Http\Kernel;
use iProtek\Core\Helpers\PayHttp;
use Illuminate\Support\Facades\Gate;
use iProtek\Core\Console\Commands\FileImportBatchCommand;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Console\Scheduling\Schedule;

use Illuminate\Support\Facades\Event;
use Illuminate\Session\Events\SessionRegenerated;
use iProtek\Core\Listeners\LogSessionRegeneration;

use Illuminate\Support\Facades\URL;

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
        /*
        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Session\Events\SessionRegenerated\SessionRegenerated::class,
            [LogSessionRegeneration::class, 'handle']
        );
        */
        if (config('session.secure') === true) {
            URL::forceScheme('https');
        }


        \Illuminate\Support\Facades\Event::listen(
            \Illuminate\Session\Events\SessionRegenerated\SessionRegenerated::class,
            \iProtek\Core\Listeners\LogSessionRegeneration::class
        );

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
        /*
        $target = base_path('packages').'/iprotek/core/public';//__DIR__.'/../public';
        if (!is_dir($target)) {
            $target = base_path('vendor').'/iprotek/core/public';
        }*/
        $target = __DIR__.'/../public';
        $target = realpath($target);

        if($target){
            $link = public_path('iprotek');
            // Create symbolic link if it doesn't exist
            if (!file_exists($link)) {
                symlink($target, $link);
            }
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

    public function booted($callback){
        
        $this->app->booted(function () {
            //$schedule = $this->app->make(Schedule::class);
             $schedule = app(Schedule::class);
            // Schedule your command
            //$schedule->command('mypackage:run-task')->dailyAt('13:00');

            // Or inline task
            // $schedule->call(function () {
            //     Log::info('Running scheduled task from package...');
            // })->everyFiveMinutes();
            $schedule->command('file-import-batch:process')
            ->everyMinute()
            ->onOneServer()
            ->runInBackground()
            ->withoutOverlapping();

            $schedule->command('file-import-batch-data:process')
            ->cron("*/2 * * * *")
            ->onOneServer()
            ->runInBackground()
            ->withoutOverlapping();


        });
    }
}