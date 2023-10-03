<?php

namespace App\Providers;

use App\Http\Controllers\Auth\GiceProvider;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local', 'develop', 'staging', 'production')) {
            //$this->app->register(\Cuonggt\Dibi\DibiServiceProvider::class);
            //$this->app->register(DibiServiceProvider::class);
            //$this->app->register(\Laravel\Telescope\TelescopeServiceProvider::class);
            //$this->app->register(TelescopeServiceProvider::class);
            $this->app->register(\Laravel\Sanctum\SanctumServiceProvider::class);
            $this->app->register(\Laravel\Horizon\HorizonServiceProvider::class);
            $this->app->register(HorizonServiceProvider::class);
            $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'gice',
            function ($app) use ($socialite) {
                $config = $app['config']['services.gice'];

                return $socialite->buildProvider(GiceProvider::class, $config);
            }
        );
        $socialite->extend(
            'eveonline',
            function ($app) use ($socialite) {
                $config = $app['config']['services.eveonline'];

                return $socialite->buildProvider(EVEOnlineSocialiteProvider::class, $config);
            }
        );
    }
}
