<?php


declare(strict_types=1);

namespace App\Domain\Commission\Service;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Enum\ClientType;
use App\Domain\Commission\Enum\OperationType;

final class PrivateDepositFeeCalculator implements FeeCalculator
{
    public function __construct(
        private readonly CurrencyCalculator $currencyCalculator
    ) {
    }

    public function calculate(Commission $commission): float
    {
        $amount = $this->currencyCalculator->calculateRate($commission);

        return $amount * 0.0003;
    }

    public function support(Commission $commission): bool
    {
        return $commission->getClientType() === ClientType::Private
            && $commission->getOperationType() === OperationType::Deposit;
    }
}