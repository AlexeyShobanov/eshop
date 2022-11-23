<?php

declare(strict_types=1);

namespace App\View\Composers;

use App\Menu\Menu;
use App\Menu\MenuItem;
use Illuminate\View\View;

final class NavigationComposer
{
    public function compose(View $view): void
    {
        $menu = Menu::make()
            ->add(MenuItem::make(route('home'), 'Главная'))
//                    ->addIf(true, MenuItem::make(route('catalog'), 'Каталог')) // можно использовать если нужно добавлять пункт меню по уловию
            ->add(MenuItem::make(route('catalog'), 'Каталог'));

        $view->with('menu', $menu);
    }

}