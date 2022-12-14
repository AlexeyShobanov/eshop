<?php

namespace App\Providers;

use App\Filters\BrandFilter;
use App\Filters\PriceFilter;
use Domain\Catalog\Filters\FilterManager;
use Domain\Catalog\Sorters\Sorter;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function register()
    {
        // объявляем FilterManager как singleton, чтобы создавался только один инстанс
        $this->app->singleton(FilterManager::class);
    }

    public function boot(): void
    {
        // регистрируем фильтры
        app(FilterManager::class)->registeredFilters([
                new PriceFilter(),
                new BrandFilter(),
            ]
        );

        $this->app->bind(Sorter::class, function () {
            return new Sorter([
                'title',
                'price'
            ]);
        });
    }
}
