<?php

namespace App\Providers;

use App\Http\Kernel;
use Carbon\CarbonInterval;
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
//        Model::preventLazyLoading(
//            !app()->isProduction()
//        ); // отключение ленивой загрузки для отображения ошибок в среде разработки
//        Model::preventSilentlyDiscardingAttributes(
//            !app()->isProduction()
//        ); // ошибка при поптке записать в поля не включенные в fillable
        Model::shouldBeStrict(
            !app()->isProduction()
        ); // содержит методы preventLazyLoading и preventSilentlyDiscardingAttributes плюс
        // preventAccessingMissingAttributes, который позволяет выдавать ошибку при обращении к несуществующему полю (атрибуту) модели

        DB::whenQueryingForLongerThan(CarbonInterval::second(5), function (Connection $connection) {
            logger()
                ->channel('telegram')
                ->debug('whenQueryingForLongerThan: ' . $connection->totalQueryDuration());
        }); // если запросы к БД выполняются больше указанного кол-ва миллисекунт выполняется оповещение/логирование.
        // Оценивается общее время конекта (от начала до последнего запроса)

        DB::listen(function ($query) {
            if ($query->time > 500) {
                logger()
                    ->channel('telegram')
                    ->debug('listenDB: ' . $query->sql, $query->bindings);
            }
        });  // позволяет мониторить каждый запрос к БД (в том число время или тело запроса)

//        обработка ситуации с долгим request
        $kernel = app(Kernel::class);
        $kernel->whenRequestLifecycleIsLongerThan(
            CarbonInterval::second(5),
            function () {
                logger()
                    ->channel('telegram')
                    ->debug('whenRequestLifecycleIsLongerThan: ' . request()->url());
            }
        );
    }
}
