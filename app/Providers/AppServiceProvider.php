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
        Model::preventLazyLoading(
            !app()->isProduction()
        ); // отключение ленивой загрузки для отображения ошибок в среде разработки
        Model::preventSilentlyDiscardingAttributes(
            !app()->isProduction()
        ); // ошибка при поптке записать в поля не включенные в fillable

        DB::whenQueryingForLongerThan(1000, function (Connection $connection) {
            logger()
                ->channel('telegram')
                ->debug('whenQueryingForLongerThan: ' . $connection->query()->toSql());
        }); // если запросы к БД выполняютс больше указанного кол-ва миллисекунт выполняется оповещение/логирование

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
