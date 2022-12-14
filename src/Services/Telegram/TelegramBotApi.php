<?php

declare(strict_types=1);

namespace Services\Telegram;

use Illuminate\Support\Facades\Http;
use Services\Telegram\Exceptions\TelegramBotApiException;
use Throwable;

use function report;

final class TelegramBotApi
{
    public const HOST = 'https://api.telegram.org/bot';

    public static function sendMessage(string $token, int $chatId, string $text): bool
    {
        try {
            $response = Http::get(self::HOST . $token . '/sendMessage', [
                'chat_id' => $chatId,
                'text' => $text,
            ])->throw(); // throw() позволяет еще поймать все серверные и клиентские ошибки 4хх и 5хх
            return $response->ok();
        } catch (Throwable $e) {
            report(
                new TelegramBotApiException($e->getMessage())
            );  // здесь мы глушим Exception, но генерим репорт в стандартные каналы
            return false;
        }
    }
}
