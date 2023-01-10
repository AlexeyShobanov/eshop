<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Support\SessionRegenerator;
use Throwable;

use function redirect;
use function route;

class SocialAuthController extends Controller
{
    public function redirect(string $driver): \Symfony\Component\HttpFoundation\RedirectResponse|RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();
        } catch (Throwable $e) {
            throw new DomainException('Драйвер не поддерживается');
        }
    }

    public function callback(string $driver): RedirectResponse
    {
        if ($driver !== 'github') {
            throw new DomainException('Произошла ошибка или драйвер не поддерживается');
        }

        $githubUser = Socialite::driver($driver)->user();

        $user = User::query()->updateOrCreate([
            $driver . '_id' => $githubUser->id,
        ], [
            'name' => $githubUser->name ?? $githubUser->email, // если нет имени можно отдать email
            'email' => $githubUser->email,
            'email_verified_at' => now(),
            'password' => bcrypt(str()->random(10)),
        ]);

//        auth()->login($user);
//
//        request()->session()->regenerate();

        SessionRegenerator::run(fn() => auth()->login($user));

        return redirect()
            ->intended(route('home'));
    }
}
