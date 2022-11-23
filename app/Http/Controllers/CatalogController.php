<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Domain\Catalog\Models\Category;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;

class CatalogController extends Controller
{

    public function __invoke(?Category $category): Factory|View|Application
    {
        $categories = CategoryViewModel::make()->catalogPage();
//        $brands = BrandViewModel::make()->catalogPage(); // перенесено на уровень фильтров

        $products = Product::query()
            ->select(['id', 'title', 'slug', 'price', 'thumbnail'])
            ->when(request('s'), function (Builder $query) {
                $query->whereFullText(['title', 'text'], request('s'));
            })
            ->when($category->exists, function (Builder $query) use ($category) {
                $query->whereRelation(
                    'categories',
                    'categories.id',
                    '=',
                    $category->id
                );
            })
            ->filtered()
            ->sorted()
            ->paginate(6);

////        реализация поиска через Scout
//        $products = Product::search('test')
//            ->query(function (Builder $query) use ($category) {
//                $query->select(['id', 'title', 'slug', 'price', 'thumbnail'])
//                    ->when($category->exists, function (Builder $query) use ($category) {
//                        $query->whereRelation(
//                            'categories',
//                            'categories.id',
//                            '=',
//                            $category->id
//                        );
//                    })
//                    ->filtered()
//                    ->sorted();
//            })
//            ->paginate(6);

        return view('catalog.index', [
            'products' => $products,
            'categories' => $categories,
//            'brands' => $brands,
            'category' => $category
        ]);
    }
}
