<?php

declare(strict_types=1);

namespace Support\Traits\Models;

use Illuminate\Support\Facades\File;

use function route;

trait HasThumbnail
{
    abstract protected function thumbnailDir(): string;

    public function makeThumbnail(string $size, string $method = 'resize'): string
    {
        return route('thumbnail', [
            'size' => $size,
            'dir' => $this->thumbnailDir(),
            'method' => $method,
            'file' => File::basename($this->{$this->thumbnailColumn()})
        ]);
    }

//    если поле с картинкой не thunbnail, нужно только переопределить эту функцию, заменив возвращаемую строку
    protected function thumbnailColumn(): string
    {
        return 'thumbnail';
    }
}
