<?php

declare(strict_types=1);

namespace Domain\Cart\Contracts;

interface CartIdentityStorageContract
{
    // метод забирает индентификатор используемого хранилища
    public function get(): string;
}