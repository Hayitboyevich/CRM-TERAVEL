<?php

namespace App\Providers;

use App\Models\Company;
use App\Models\OrderItems;
use App\Models\OrderTourPackage;
use App\Observers\OrderItemsObserver;
use App\Observers\OrderTourPackageObserver;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Laravel\Cashier\Cashier;

class AppServiceProvider extends ServiceProvider
{

    public function boot()
    {

        Cashier::useCustomerModel(Company::class);

        Schema::defaultStringLength(191);

        if (app()->environment('development')) {
            $this->app->register(IdeHelperServiceProvider::class);
        }

        CarbonInterval::macro('formatHuman', function ($totalMinutes, $seconds = false): string {
            if ($seconds) {
                return static::seconds($totalMinutes)->cascade()->forHumans(['short' => true, 'options' => 0]);
            }

            return static::minutes($totalMinutes)->cascade()->forHumans(['short' => true, 'options' => 0]);
        });

        OrderItems::observe(OrderItemsObserver::class);

    }

    /**
     * Register any application services.
     *
     * @return void
     */

    public function register()
    {
        Cashier::ignoreMigrations();
//        URL::forceScheme('https');
//        if (config('app.redirect_https')) {
//            $this->app['request']->server->set('HTTPS', true);
//        }
    }

}
