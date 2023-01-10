<?php

declare(strict_types=1);

namespace Support;

use App\Events\AfterSessionRegenereted;
use Closure;

final class SessionRegenerator
{
    public static function run(Closure $callback = null): void
    {
        $old = request()->session()->getId();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        if (!is_null($callback)) {
            $callback();
        }

        event(
            new AfterSessionRegenereted(
                $old,
                request()->session()->getId()
            )
        );
    }
}