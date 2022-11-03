<?php

declare(strict_types=1);

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Auth\Events\Registered;
use Throwable;

final class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(string $name, string $email, string $password)
    {
        if (User::query()->where('email', $email)->exists()) {
            throw new DomainException('Этот email уже зарегистрирован!');
        }

        try {
            $user = User::query()->create([
                'name' => $name,
                'email' => $email,
                'password' => bcrypt($password),
            ]);
        } catch (Throwable $e) {
            throw new DomainException('Ошибка при регистрации!');
        }
        event(new Registered($user));
        auth()->login($user);

        return $user;
    }
}
