<?php


declare(strict_types=1);

namespace App\Domain\Commission\Service;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Enum\ClientType;
use App\Domain\Commission\Enum\OperationType;

final class BusinessWithdrawFeeCalculator implements FeeCalculator
{
    public function __construct(
        private readonly CurrencyCalculator $currencyCalculator
    ) {
    }

    public function calculate(Commission $commission): float
    {
        return $this->currencyCalculator->calculateRate($commission) * 0.005;
    }

    public function support(Commission $commission): bool
    {
        return $commission->getClientType() === ClientType::Business
            && $commission->getOperationType() === OperationType::Withdraw;
    }
}