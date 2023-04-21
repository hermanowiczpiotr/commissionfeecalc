<?php


declare(strict_types=1);

namespace App\Domain\Commission\Entity;

final class CurrencyRate
{
    public function __construct(
        private string $code,
        private float $rate
    ) {
    }

    public static function create(
        string $code,
        float $rate
    ): self {
        return new self($code, $rate);
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getRate(): float
    {
        return $this->rate;
    }
}