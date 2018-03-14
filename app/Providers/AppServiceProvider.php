<?php

namespace App\Providers;

use App\Domain;
use App\Link;
use App\Observers\DomainObserver;
use App\Observers\LinkObserver;
use App\Observers\UserObserver;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Setup the model observers
        Link::observe(LinkObserver::class);
        Domain::observe(DomainObserver::class);
        User::observe(UserObserver::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // If we're not in production register the ide helper service provider
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
