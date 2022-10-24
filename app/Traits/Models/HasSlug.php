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
            if (!$model->slug) {
                $slug = str($model->{self::slugFrom()})->slug();
                if ($model->newQuery()->where('slug', $slug)->get()->isEmpty()) {
                    $slug .= '-' . self::$increment;
                    self::$increment += 1;
                }
                $model->slug = str($slug)
                    ->slug();
            }
        });
    }

//если slug формируется не из title, то достаточно будет переопределить фуцию slugFrom в моделе, заменив в return title на нужное поле
    public
    static function slugFrom(): string
    {
        return 'title';
    }
}
