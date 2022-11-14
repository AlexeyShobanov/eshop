<?php

namespace Domain\Catalog\Providers;

// use Illuminate\Support\Facades\Gate;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Domain\Catalog\Observers\BrandObserver;
use Domain\Catalog\Observers\CategoryObserver;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{

    public function boot(): void
    {
        Brand::observe(BrandObserver::class);
        Category::observe(CategoryObserver::class);
    }

    public function register(): void
    {
        $this->app->register(
            ActionsServiceProvider::class
        );
    }
}
