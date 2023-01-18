<?php

declare(strict_types=1);

namespace Domain\Order\States;

use Domain\Order\Events\OrderStatusChanged;
use Domain\Order\Models\Order;
use InvalidArgumentException;

abstract class OrderState
{
    // в этом свойстве укажем какие стейты доступны для класса (в наследниках)
    protected array $allowedTransitions = [];

    public function __construct(
        protected Order $order
    ) {
    }

//    проверяем может ли меняться этот класс
    abstract public function canBeChanged(): bool;

//   получаем значение состояния из БД
    abstract public function value(): string;

    //   значение стейта на человеческом языке, что нужно для отображения
    abstract public function humanValue(): string;

    public function transitionTo(OrderState $state): void
    {
        if (!$this->canBeChanged()) {
            throw new InvalidArgumentException(
                'Status can`t be changed'
            );
        }

        if (!in_array(get_class($state), $this->allowedTransitions)) {
            throw new InvalidArgumentException(
                "No transition for {$this->order->status->value()} to {$state->value()}"
            );
        }

//        если все ок апдейтим статус заказа, делаем это через updateQuietly чтобы автоматически не генерировалось событие
        $this->order->updateQuietly([
            'status' => $state->value()
        ]);

        event(
            new OrderStatusChanged(
                $this->order,
                $this->order->status,
                $state
            )
        );
    }
}