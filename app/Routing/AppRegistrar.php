<?php

declare(strict_types=1);

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

final class AppRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {
            Route::get('/', HomeController::class)->name('home');

//           в рамках пакета image-intervention method - это метод обработки, size - размер изображения, file - файл изображания
            Route:: get('storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
                ->where('method', 'resize|crop|fit') //возможные значения для метода
                ->where('size', '\d+x\d+') //регулярка, что в размере должны быть цифры
                ->where(
                    'file',
                    '.+\.(png|jpg|jpeg|gif|bmp)$'
                ) // регулярка для файла - название, точка, допустимые расширения, конец файла
                ->name('thumbnail');
        });
    }

}
