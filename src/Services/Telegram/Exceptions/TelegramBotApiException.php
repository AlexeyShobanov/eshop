<?php

declare(strict_types=1);

namespace Services\Telegram\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;

use function response;

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
