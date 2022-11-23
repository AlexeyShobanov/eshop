<?php

declare(strict_types=1);

namespace Domain\Catalog\Filters;

final class FilterManager
{
    // добавляем фильтры как атрибуты
    public function __construct(
        protected array $items = []
    ) {
    }

    // регистрируем фильтры
    public function registeredFilters($items): void
    {
        $this->items = $items;
    }

    // получаем все объявленные фильтры
    public function items(): array
    {
        return $this->items;
    }
}