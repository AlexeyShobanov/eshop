<?php

declare(strict_types=1);

namespace Domain\Order\States;

// создаем стайт для нового заказа
final class PaidOrderState extends OrderState
{
// перечень возможный стейтов перехода
    protected array $allowedTransitions = [
        CancelledOrderState::class,
    ];

    // указываем может ли это стейт переходит в другой стай (не является ли он конечным)
    public function canBeChanged(): bool
    {
        return true;
    }

//    указываем значение стейта на уровне enum
    public function value(): string
    {
        return 'paid';
    }

    //   название стейта на человеческом языке
    public function humanValue(): string
    {
        return 'Оплачен';
    }
}