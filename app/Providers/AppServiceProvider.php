<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        config(['app.locale' => 'es']);
        Carbon::setLocale('es');
        $this->commands([
            \App\Console\Commands\RestoreDatabase::class,
        ]);
        
        date_default_timezone_set('America/La_Paz');
        if (env('APP_ENV') == 'local') {
            URL::forceScheme('http');
        }
    }
}
