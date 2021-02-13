<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\ProductRepository;
use App\Repositories\CategoryRepository;

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
        $this->app->singleton('App\Interfaces\ProductInterface', function () {
            return new ProductRepository(new CategoryRepository());
        });
        $this->app->singleton('App\Interfaces\CategoryInterface', function () {
            return new CategoryRepository();
        });
    }
}
