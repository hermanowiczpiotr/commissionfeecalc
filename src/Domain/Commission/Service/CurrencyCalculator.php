<?php


declare(strict_types=1);

namespace App\Domain\Commission\Service;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Enum\CurrencyType;
use App\Domain\Commission\Repository\CurrencyRateRepository;

class CurrencyCalculator
{
    public function __construct(
        private readonly CurrencyRateRepository $currencyRateRepository
    ) {
    }

    public function calculateRate(Commission $commission): float
    {
        if ($commission->getCurrencyType() === CurrencyType::EUR) {
            return $commission->getAmount();
        }

        $currencyRate = $this->currencyRateRepository->getRateByCode($commission->getCurrencyType());

        return $commission->getAmount() / round($currencyRate->getRate(), 2, PHP_ROUND_HALF_UP);
    }
}