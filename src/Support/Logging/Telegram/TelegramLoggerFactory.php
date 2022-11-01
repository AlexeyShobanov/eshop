<?php

declare(strict_types=1);

namespace Support\Logging\Telegram;

use Monolog\Logger;

final class TelegramLoggerFactory
{
    public function __invoke(array $config): Logger
    {
        $logger = new Logger('telegram'); // здесь в качестве аргумента указан конфиг из logging.conf
        $logger->pushHandler(new TelegramLoggerHandler($config));
        return $logger;
    }
}
