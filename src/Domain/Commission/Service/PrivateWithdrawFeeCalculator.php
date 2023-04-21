<?php


declare(strict_types=1);

namespace App\Domain\Commission\Service;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Enum\ClientType;
use App\Domain\Commission\Enum\OperationType;

final class PrivateWithdrawFeeCalculator implements FeeCalculator
{
    public function __construct(
        private readonly FreeWithdrawLimitCalculator $freeWithdrawLimitCalculator,
        private readonly CurrencyCalculator $currencyCalculator
    ) {
    }

    public function calculate(Commission $commission): float
    {
        $amount = $this->currencyCalculator->calculateRate($commission);
        $freeLimit = $this->freeWithdrawLimitCalculator->calculate($commission);

        return max(($amount - $freeLimit) * 0.003, 0.0);
    }

    public function support(Commission $commission): bool
    {
        return $commission->getClientType() === ClientType::Private
            && $commission->getOperationType() === OperationType::Withdraw;
    }
}