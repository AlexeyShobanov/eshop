<?php

declare(strict_types=1);

namespace Support\ValueObjects;

use InvalidArgumentException;
use Stringable;
use Support\Traits\Makeable;

final class Price implements Stringable
{
    use Makeable;

    private array $currencies = [
        'RUB' => '₽'
    ];

    public function __construct(
        private readonly int $value,
        private readonly string $currency = 'RUB',
        private readonly int $precision = 100 // кол-во знаков после запятой, 2 знака, для этого делим на 100

    )
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Price must be more then zero');
        }

        if (!$this->currencies[$this->currency]) {
            throw new InvalidArgumentException('Currency not allowed');
        }
    }

    // возвращает полное занчение как int
    public function fullValue(): int
    {
        return $this->value * $this->precision;
    }

    // возвращает значение как int
    public function raw(): int
    {
        return $this->value;
    }

    // получение трансформированного значения
    public function value(): float|int
    {
        return $this->value / $this->precision;
    }

    // получение названия валюты
    public function currency(): string
    {
        return $this->currency;
    }

    // получение символа валюты
    public function symbol(): string
    {
        return $this->currencies[$this->currency];
    }

    public function __toString(): string
    {
        return number_format($this->value(), 0, ',', ' ')
            . ' ' . $this->symbol();
    }
}
