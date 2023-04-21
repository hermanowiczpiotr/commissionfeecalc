<?php


declare(strict_types=1);

namespace App\Domain\Commission\Repository;

use App\Domain\Commission\Entity\CurrencyRate;
use App\Domain\Commission\Enum\CurrencyType;

interface CurrencyRateRepository
{
    public function add(CurrencyRate $currencyRate): void;
    public function getRateByCode(CurrencyType $currencyType): CurrencyRate;
}