<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [ 
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [ 
        ],
        "customer"=>[ 
            \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class
        ],
        'api' => [            
            \App\Http\Middleware\EncryptCookies::class,
            //'throttle:60,1', 
            \Illuminate\Session\Middleware\StartSession::class,  
        ],
        "web-visits"=>[
            \iProtek\Core\Http\Middleware\TrackVisitor::class
        ]   
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'auth.customer' => \iProtek\Core\Http\Middleware\AuthenticateCustomer::class,
        'pay.account'=>\iProtek\Core\Http\Middleware\PayAppUserAccount::class,
        'pay.api'=>\iProtek\Core\Http\Middleware\PayAppUserAccountApi::class,
        'super_admin'=>\iProtek\Core\Http\Middleware\SuperAdmin::class,
        'auth_web_pay_checker'=>\iProtek\Core\Http\Middleware\AuthWebPayChecker::class,
    ];
}
