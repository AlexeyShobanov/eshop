<?php

declare(strict_types=1);

namespace Domain\Auth\Actions;

use Domain\Auth\Contracts\RegisterNewUserContract;
use Domain\Auth\DTOs\NewUserDTO;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Auth\Events\Registered;
use Throwable;

final class RegisterNewUserAction implements RegisterNewUserContract
{
    public function __invoke(NewUserDTO $data)
    {
        if (User::query()->where('email', $data->email)->exists()) {
            throw new DomainException('Этот email уже зарегистрирован!');
        }

        try {
            $user = User::query()->create([
                'name' => $data->name,
                'email' => $data->email,
                'password' => bcrypt($data->password),
            ]);
        } catch (Throwable $e) {
            throw new DomainException('Ошибка при регистрации!');
        }
        event(new Registered($user));

        return $user;
    }
}
