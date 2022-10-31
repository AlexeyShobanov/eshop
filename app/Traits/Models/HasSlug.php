<?php

declare(strict_types=1);

namespace App\Traits\Models;

use Illuminate\Database\Eloquent\Model;

trait HasSlug
{
    private static int $increment = 1;

    protected static function bootHasSlug()
    {
        static::creating(function (Model $model) {
            $model->makeSlug();
        });
    }

    protected function makeSlug(): void
    {
        if (is_null($this->{$this->slugColumn()})) {
            $this->{$this->slugColumn()} = $this->slugUniq(
                str($this->{$this->slugFrom()})
                    ->slug()
                    ->value()
            );
        }
    }

    private function slugUniq(string $slug): string
    {
        $originalSlug = $slug;
        $index = 1;

        while ($this->isSlugExists($slug)) {
            $slug = $originalSlug . '-' . $index;
            $index += 1;
        }

        return $slug;
    }

    private function isSlugExists($slug): bool
    {
        $query = $this->newQuery()->where(self::slugColumn(), $slug);
        return $query->exists();
    }


//      если slug формируется не из title, то достаточно будет переопределить фуцию slugFrom в моделе, заменив в return title на нужное поле
    protected function slugFrom(): string
    {
        return 'title';
    }

//    определяем имя поляд для которого нужно сформировать уникальное значение. в случае если это не slug, достаточно будет переопределить это поле
    protected function slugColumn(): string
    {
        return 'slug';
    }
}
