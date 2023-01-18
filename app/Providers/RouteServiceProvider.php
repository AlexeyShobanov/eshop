<?php

namespace App\Providers;

use App\Routing\AppRegistrar;
use App\Routing\AuthRegistrar;
use App\Routing\CartRegistrar;
use App\Routing\CatalogRegistrar;
use App\Routing\ProductRegistrar;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\RouteRegistrar;
use Illuminate\Support\Facades\RateLimiter;
use RuntimeException;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    protected array $registrars = [
        \App\Routing\OrderRegistrar::class,
        CartRegistrar::class,
        AppRegistrar::class,
        AuthRegistrar::class,
        CatalogRegistrar::class,
        ProductRegistrar::class,
    ];

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function (Registrar $router) {
            $this->mapRoutes($router, $this->registrars);
        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
//      кастомный рейтлимит для запросов к сайту
        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(1000)
                ->by($request->user()?->id ?: $request->ip())
                ->response(function (Request $request, array $headers) {
                    return response('Take it easy', Response::HTTP_TOO_MANY_REQUESTS, $headers);
                });
        });

//        кастомный рейтлимит для страницы с аутентификацией
        RateLimiter::for('auth', function (Request $request) {
            return Limit::perMinute(20)->by($request->ip());
        });

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }

//   карта роутов для DDD
    protected function mapRoutes(Registrar $router, array $registrars): void
    {
        foreach ($registrars as $registrar) {
            if (!class_exists($registrar) || is_subclass_of($registrar, RouteRegistrar::class)) {
                throw new RuntimeException(
                    sprintf(
                        'Cannot map routes \'%s\', it is not a valid routes class',
                        $registrar
                    )
                );
            }

            (new $registrar)->map($router);
        }
    }
}
