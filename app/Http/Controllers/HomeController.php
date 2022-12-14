<?php

namespace App\Http\Controllers;

use Domain\Catalog\ViewModels\BrandViewModel;
use Domain\Catalog\ViewModels\CategoryViewModel;
use Domain\Product\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 *
 */
class HomeController extends Controller
{
    public function __invoke(): Factory|View|Application
    {
//        вынесено в CategoryViewModel
//        $categories = Category::query()
//            ->homePage()
//            ->get();

        $categories = CategoryViewModel::make()->homePage();
        $brands = BrandViewModel::make()->homePage();

        $products = Product::query()
            ->homePage()
            ->get();


        return view(
            'index',
            compact(
                'categories',
                'brands',
                'products'
            )
        );
    }
}
