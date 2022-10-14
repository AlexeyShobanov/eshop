<?php

namespace App\Providers;

use Illuminate\Database\Connection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Model::preventLazyLoading(!app()->isProduction()); // отключение ленивой загрузки для отображения ошибок в среде разработки
        Model::preventSilentlyDiscardingAttributes(!app()->isProduction());  //ошибка при поптке записать в поля не включенные в fillable

        DB::whenQueryingForLongerThan(500, function (Connection $connection) {
            //  TODO здесь должно быть логирование...
        }); // если запросы к БД выполняютс больше указанного кол-ва миллисекунт выполняется оповещение/логирование

//        TODO обработка ситуации с долким request
    }
}
