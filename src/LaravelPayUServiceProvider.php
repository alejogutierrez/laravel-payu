<?php

namespace Alexo\LaravelPayU;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class LaravelPayUServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/payu.php' => config_path('payu.php'),
            __DIR__ . '/model/Order.php' => app_path('Order.php'),
        ]);

        $this->mergeConfigFrom(
            __DIR__ . '/config/payu.php', 'payu'
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
