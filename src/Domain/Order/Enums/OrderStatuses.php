<?php

declare(strict_types=1);

namespace Domain\Order\Enums;

use Domain\Order\Models\Order;
use Domain\Order\States\CancelledOrderState;
use Domain\Order\States\NewOrderState;
use Domain\Order\States\OrderState;
use Domain\Order\States\PaidOrderState;
use Domain\Order\States\PendingOrderState;

enum OrderStatuses: string
{
    case New = 'new';
    case Pending = 'pending';
    case Paid = 'paid';
    case Cancelled = 'cancelled';

    public function createState(Order $order): OrderState
    {
//        можно через консткцию switch-case, но по-современному сделано через match
        return match ($this) {
            OrderStatuses::New => new NewOrderState($order),
            OrderStatuses::Pending => new PendingOrderState($order),
            OrderStatuses::Paid => new PaidOrderState($order),
            OrderStatuses::Cancelled => new CancelledOrderState($order)
        };
    }
}