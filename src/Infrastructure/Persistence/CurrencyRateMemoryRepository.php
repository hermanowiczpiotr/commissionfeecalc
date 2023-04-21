<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Commission\Entity\CurrencyRate;
use App\Domain\Commission\Enum\CurrencyType;
use App\Domain\Commission\Repository\CurrencyRateRepository;

final class CurrencyRateMemoryRepository implements CurrencyRateRepository
{
    /** @var CurrencyRate[] */
    private array $currencyRates = [];

    public function add(CurrencyRate $currencyRate): void
    {
        $this->currencyRates[] = $currencyRate;
    }

    public function getRateByCode(CurrencyType $currencyType): CurrencyRate
    {
        foreach ($this->currencyRates as $currencyRate) {
            if ($currencyRate->getCode() === $currencyType->value) {
                return $currencyRate;
            }
        }

        throw new \Exception();
    }
}