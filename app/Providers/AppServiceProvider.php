<?php

namespace App\Providers;

use App;
use App\Interfaces\ProductInterface;
use App\Interfaces\CategoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Services\ProductService;
use App\Services\CategoryService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ProductService::class, function () {
            return new ProductService(App::make(ProductInterface::class));
        });
        $this->app->singleton(CategoryService::class, function () {
            return new CategoryService(App::make(CategoryInterface::class));
        });
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
