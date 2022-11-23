<?php

namespace App\Providers;

use App\View\Composers\NavigationComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Vite::macro('image', fn($asset) => $this->asset("resources/images/$asset"));

//        случай с описанием подключения меню через callback-функцию
//        View::composer('*', function ($view) {
//            $view->with(
//                'menu',
//                Menu::make()
//                    ->add(MenuItem::make(route('home'), 'Главная'))
//                    ->add(MenuItem::make(route('catalog'), 'Каталог'))
//            );
//        });

        View::composer('*', NavigationComposer::class);
    }
}
