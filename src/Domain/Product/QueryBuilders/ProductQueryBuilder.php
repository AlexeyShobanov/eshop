<?php

declare(strict_types=1);

namespace Domain\Product\QueryBuilders;

use Domain\Catalog\Facades\Sorter;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;

final class ProductQueryBuilder extends Builder
{
    //    создаем скоп для главной страницы
    public function homePage(): ProductQueryBuilder
    {
        return $this->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(8);
    }

    public function sorted(): Builder|ProductQueryBuilder
    {
        return Sorter::run($this);
    }

    public function filtered(): ProductQueryBuilder
    {
        foreach (filters() as $filter) {
            $query = $filter->apply($this);
        }

        return $query ?? $this;
    }

    public function withCategory(Category $category): ProductQueryBuilder
    {
        return $this->when($category->exists, function (Builder $query) use ($category) {
            $query->whereRelation(
                'categories',
                'categories.id',
                '=',
                $category->id
            );
        });
    }

    public function search(): ProductQueryBuilder
    {
        return $this->when(request('s'), function (Builder $query) {
            $query->whereFullText(['title', 'text'], request('s'));
        });
    }

}
