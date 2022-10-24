<?php

namespace App\Providers;

use App\Fake\FakerImageProvider;
use App\Http\Kernel;
use Carbon\CarbonInterval;
use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Model::shouldBeStrict(
            !app()->isProduction()
        ); // содержит методы preventLazyLoading и preventSilentlyDiscardingAttributes плюс

        DB::listen(function ($query) {
            if ($query->time > 500) {
                logger()
                    ->channel('telegram')
                    ->debug('query longer then 500 ms: ' . $query->sql, $query->bindings);
            }
        });  // позволяет мониторить каждый запрос к БД (в том число время или тело запроса)

//        обработка ситуации с долгим request
//        $kernel = app(Kernel::class);
        app(Kernel::class)->whenRequestLifecycleIsLongerThan(
            CarbonInterval::second(5),
            function () {
                logger()
                    ->channel('telegram')
                    ->debug('whenRequestLifecycleIsLongerThan: ' . request()->url());
            }
        );
    }

    public function register()
    {
        $this->app->singleton(Generator::class, function () {
            $faker = Factory::create();
            $faker->addProvider(new FakerImageProvider($faker));
            return $faker;
        });
    }
}
