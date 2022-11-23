<?php

namespace App\Models;

use Domain\Catalog\Models\Brand;
use Domain\Catalog\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;
use Support\Casts\PriceCast;
use Support\Traits\Models\HasSlug;
use Support\Traits\Models\HasThumbnail;

class Product extends Model
{
    use HasFactory;
    use HasSlug;
    use HasThumbnail;
    use Searchable;

    protected $fillable = [
        'title',
        'slug',
        'brand_id',
        'thumbnail',
        'price',
        'on_home_page',
        'sorting',
        'text'
    ];

    protected $casts = [
        'price' => PriceCast::class
    ];

    protected function thumbnailDir(): string
    {
        return 'products';
    }

//    // поля поиска для scout
//    #[SearchUsingFullText(['title', 'text'])]
//    public function toSearchableArray()
//    {
//        return [
//            'title' => $this->title,
//            'text' => $this->text
//        ];
//    }

    //    скоп для фильтра
    public function scopeFiltered(Builder $query)
    {
//        это реализация филтра через скоп
//        $query->when(request('filters.brands'), function (Builder $q) {
//            $q->whereIn('brand_id', request('filters.brands'));
//        })->when(request('filters.price'), function (Builder $q) {
//            $q->whereBetween('price', [
//                Price::make((int)request('filters.price.from', 0))->fullValue(),
//                Price::make((int)request('filters.price.to', 100000))->fullValue()
////                request('filters.price.from', 0) * 100,
////                request('filters.price.to', 100000) * 100
//            ]);
//        });

        // это реализация через класс
        foreach (filters() as $filter) {
            $query = $filter->apply($query);
        }
    }

    //    скоп для сортировки
    public function scopeSorted(Builder $query)
    {
        $query->when(request('sort'), function (Builder $q) {
            $column = request()->str('sort');

            if ($column->contains(['price', 'title'])) {
                $direction = $column->contains('-') ? 'DESC' : 'ASC';
                $q->orderBy((string)$column->remove('-'), $direction);
            }
        });
    }

    //    создаем скоп для главной страницы
    public function scopeHomePage(Builder $query)
    {
        $query->where('on_home_page', true)
            ->orderBy('sorting')
            ->limit(8);
    }

    //    создаем генерацию слага в boot - теперь этоного нет, вынесено в трейт HasSlug
    protected static function boot()
    {
        parent::boot();
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class)
            ->withPivot('value');
    }

    public function optionValues(): BelongsToMany
    {
        return $this->belongsToMany(OptionValue::class);
    }
}
