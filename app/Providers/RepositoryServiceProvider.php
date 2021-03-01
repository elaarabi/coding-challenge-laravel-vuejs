<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\Product\CategoryRepository;
use App\Repositories\Eloquent\Product\ProductRepository;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application repositories.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Repositories\Contracts\Product\ProductInterface', function () {
            return new ProductRepository();
        });
        $this->app->singleton('App\Repositories\Contracts\Product\CategoryInterface', function () {
            return new CategoryRepository();
        });
    }
}
