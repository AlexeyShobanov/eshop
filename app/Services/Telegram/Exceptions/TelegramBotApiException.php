<?php

declare(strict_types=1);

namespace App\Services\Telegram\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

final class TelegramBotApiException extends Exception
{
    public function report()
    {
        // отправляем в логгер или телескоп или сентри
    }

    public function render(): JsonResponse
    {
        return response()->json(["error" => "TelegramBotApi Error"]);
    }
}
