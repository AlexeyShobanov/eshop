<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class ProductController extends Controller
{
    public function __invoke(Product $product): Factory|View|Application
    {
        // создаем eager load для Option через optionValues у Product
        $product->load(['optionValues.option']);

        $options = $product->optionValues->mapToGroups(function ($item) {
            return [$item->option->title => $item];
        });

        if (session()->has('also')) {
            $listProductId = collect(session()->get('also'));
            $listProductIdMod = $listProductId->filter(function ($value) use ($product) {
                return $value !== $product->id;
            });
            $viewedProducts = Product::query()->whereIn('id', $listProductIdMod)->limit(4)->get();
        }
        // можно реализовать сохранение просмотренных товаров через сессии
        session()->put('also.' . $product->id, $product->id);

        return view('product.show', [
            'product' => $product,
            'options' => $options,
            'viewedProducts' => $viewedProducts ?? $viewedProducts,
        ]);
    }
}
