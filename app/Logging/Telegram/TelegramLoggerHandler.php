<?php

declare(strict_types=1);

namespace App\Logging\Telegram;

use App\Services\Telegram\Exceptions\TelegramBotApiException;
use App\Services\Telegram\TelegramBotApi;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;

final class TelegramLoggerHandler extends AbstractProcessingHandler
{
    protected int $chatId;
    protected string $token;

    public function __construct(array $config)
    {
        $level = Logger::toMonologLevel($config['level']);
        $this->chatId = (int)$config['chat_id'];
        $this->token = $config['token'];
        $dateFormat = "Дата: d.m.Y Время: H:i:s+00:00";
        $output = "%datetime%\nУровень: %level_name%\nСообщение: %message%\n";
        $formatter = new LineFormatter($output, $dateFormat);
        parent::__construct($level);
        parent::setFormatter($formatter);
    }

    /**
     * @throws TelegramBotApiException
     */
    protected function write(array $record): void
    {
        TelegramBotApi::sendMessage(
            $this->token,
            $this->chatId,
            $record['formatted']
        );
    }
}
