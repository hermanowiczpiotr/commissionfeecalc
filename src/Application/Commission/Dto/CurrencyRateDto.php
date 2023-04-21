<?php


declare(strict_types=1);


namespace App\Application\Commission\Dto;

final class CurrencyRateDto
{
    public function __construct(
        public string $code,
        public float $rate
    ) {
    }
}