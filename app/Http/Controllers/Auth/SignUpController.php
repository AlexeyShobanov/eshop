<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\SignInFormRequest;
use Domain\Auth\Contracts\RegisterNewUserContract;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

use function redirect;
use function route;
use function view;

class SignUpController extends Controller
{
    public function page(): Factory|View|Application|RedirectResponse
    {
        return view('auth.sign-up');
    }

    public function handle(SignInFormRequest $request, RegisterNewUserContract $action): RedirectResponse
    {
//        TODO make DTOs
//        action желательно обернуть в try/catch чтобы отлавливать возможные ошибки при работе с БД
        $user = $action(
            $request->get('name'),
            $request->get('email'),
            $request->get('password')
        );

        if ($user) {
            return redirect()->route('verification.notice');
        }

        return redirect()
            ->intended(route('home')); // назад или на указанный роут по дефолту
    }
}
