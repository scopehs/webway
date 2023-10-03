<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class DibiServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->gate();
    }

    /**
     * Register the Dibi gate.
     *
     * This gate determines who can access Dibi in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewDibi', function ($user = null) {
            return in_array(optional($user)->name, [
                'Monty The Apprentice',
                'Scopeh The Master',
            ]);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
