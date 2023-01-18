<?php

declare(strict_types=1);

namespace Domain\Order\Processes;

use Domain\Order\Contracts\OrderProcessContract;
use Domain\Order\Exceptions\OrderProcessExeption;
use Domain\Order\Models\Order;

final class CheckProductQuantities implements OrderProcessContract
{

    /**
     * @throws OrderProcessExeption
     */
    public function handle(Order $order, $next)
    {
        foreach (cart()->items() as $item) {
//          проверяем что товаров больше чем их положено в конзину, для таких случаев лучше всегда создавать кастомный эксепшен
            if ($item->product->quantity < $item->quantity) {
                throw new OrderProcessExeption('Не достаточно товара');
            }
        }

        return $next($order);
    }
}