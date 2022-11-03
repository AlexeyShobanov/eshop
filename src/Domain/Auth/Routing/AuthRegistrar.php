<?php

declare(strict_types=1);

namespace Domain\Auth\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\SocialAuthController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

final class AuthRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')->group(function () {
            Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
                $request->fulfill();
                return redirect()->route('home');
            })->middleware(['auth', 'signed'])->name('verification.verify');

            Route::get('/email/verify', function () {
                return view('auth.verify-email');
            })->middleware('auth')->name('verification.notice');
            
            Route::post('/email/verification-notification', function (Request $request) {
                $request->user()->sendEmailVerificationNotification();
                return back()->with('message', 'Verification link sent!');
            })->middleware(['auth', 'throttle:6,1'])->name('verification.send');


            Route::controller(SignInController::class)->group(function () {
                Route::get('/login', 'page')->name('login');
                Route::post('/login', 'handle')
                    ->middleware('throttle:auth') // кастомный рейтлимит определенный в RouteServiceProvider
                    ->name('login.handle');

                Route::delete('/logout', 'logOut')->name('logOut');
            });

            Route::controller(SignUpController::class)->group(function () {
                Route::get('/sign-up', 'page')->name('register');
                Route::post('/sign-up', 'handle')
                    ->middleware('throttle:auth')
                    ->name('register.handle');
            });

            Route::controller(ForgotPasswordController::class)->group(function () {
                Route::get('/forgot-password', 'page')
                    ->middleware('guest')
                    ->name('forgot');

                Route::post('/forgot-password', 'handle')
                    ->middleware('guest')
                    ->name('forgot.handle');
            });

            Route::controller(ResetPasswordController::class)->group(function () {
                Route::get('/reset-password/{token}', 'page')
                    ->middleware('guest')
                    ->name('password.reset');

                Route::post('/reset-password', 'handle')
                    ->middleware('guest')
                    ->name('password.reset.handle');
            });

            Route::controller(SocialAuthController::class)->group(function () {
                Route::get('/auth/socialite/{driver}', 'redirect')
                    ->name('socialite.redirect');

                Route::get('/auth/socialite/{driver}/callback', 'callback')
                    ->name('socialite.callback');
            });
        });
    }
}
