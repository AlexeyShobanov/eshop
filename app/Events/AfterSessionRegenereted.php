<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AfterSessionRegenereted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public string $old,
        public string $current
    ) {
        //
    }
}
