<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Libraries\OptimizelyServices;

class OptimizelyServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Libraries\OptimizelyServices', function ($app) {
            $request =  $this->app->request;
            return new OptimizelyServices($request);
        });
    }
}
