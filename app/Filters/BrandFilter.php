<?php

declare(strict_types=1);

namespace App\Filters;

use Domain\Catalog\Filters\AbstractFilter;
use Domain\Catalog\ViewModels\BrandViewModel;
use Illuminate\Database\Eloquent\Builder;

final class BrandFilter extends AbstractFilter
{

    public function title(): string
    {
        return 'Бренды';
    }

    public function key(): string
    {
        return 'brands';
    }

    public function apply(Builder $query): Builder
    {
        return $query->when($this->requestValue(), function (Builder $q) {
            $q->whereIn('brand_id', $this->requestValue());
        });
    }

    public function values(): array
    {
        return BrandViewModel::make()
            ->catalogPage()
            ->pluck('title', 'id')
            ->toArray();
    }

    public function view(): string
    {
        return 'catalog.filters.brands';
    }
}