<?php

namespace App\Http\Controllers;

use App\ViewModels\CatalogViewModel;
use Domain\Catalog\Models\Category;

class CatalogController extends Controller
{

    public function __invoke(?Category $category): CatalogViewModel
    {
//         все вынесено в CategoryViewModel (Spatie)
//        $categories = CategoryViewModel::make()->catalogPage();
////        $brands = BrandViewModel::make()->catalogPage(); // перенесено на уровень фильтров
//
//        $products = Product::query()
//            ->select(['id', 'title', 'slug', 'price', 'thumbnail', 'json_properties'])
//            ->search()
//            ->withCategory($category)
//            ->filtered()
//            ->sorted()
//            ->paginate(6);

//        return view('catalog.index', new CatalogViewModel($category));

        return (new CatalogViewModel($category))
            ->view('catalog.index');
    }
}
