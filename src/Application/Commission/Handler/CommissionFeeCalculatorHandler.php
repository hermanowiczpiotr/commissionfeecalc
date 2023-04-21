<?php

declare(strict_types=1);

namespace App\Application\Commission\Handler;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Service\FeeCalculator;

final class CommissionFeeCalculatorHandler
{
    /** @param FeeCalculator[] $feeCalculators */
    public function __construct(private readonly iterable $feeCalculators)
    {
    }

    public function calculateFee(Commission $commission): float
    {
        foreach ($this->feeCalculators as $feeCalculator) {
            if($feeCalculator->support($commission)) {
                return $feeCalculator->calculate($commission);
            }
        }

        throw new \Exception();
    }
}