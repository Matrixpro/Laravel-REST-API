<?php

namespace App\Providers;

use App\Contracts\FetchProductsForUserInterface;
use App\Models\Product;
use App\Models\Queries\FetchProductsForUser;
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
        $this->app->bind(FetchProductsForUserInterface::class, FetchProductsForUser::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
