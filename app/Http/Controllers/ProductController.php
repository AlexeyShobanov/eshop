<?php

namespace App\Http\Controllers;

use Domain\Product\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __invoke(Product $product): Factory|View|Application
    {
        // создаем eager load для Option через optionValues у Product
        $product->load(['optionValues.option']);

//      Вынесено в кастомную коллекцию
//        $options = $product->optionValues->mapToGroups(function ($item) {
//            return [$item->option->title => $item];
//        });

        if (session()->has('also')) {
            $viewedProducts = Product::query()->where(function ($q) use ($product) {
                $q->whereIn('id', session('also'))
                    ->where('id', '!=', $product->id);
            })
                ->limit(4)
                ->get();
        }

        session()->put('also.' . $product->id, $product->id);

        return view('product.show', [
            'product' => $product,
            'options' => $product->optionValues->keyValues(),
            'viewedProducts' => $viewedProducts ?? [],
        ]);
    }
}
