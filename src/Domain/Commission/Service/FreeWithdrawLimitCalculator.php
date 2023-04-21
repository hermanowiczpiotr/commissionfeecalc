<?php

declare(strict_types=1);

namespace App\Domain\Commission\Service;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Repository\CommissionRepository;

class FreeWithdrawLimitCalculator
{
    private const FEE_FREE_OF_CHARGE_LIMIT = 1000.00;

    public function __construct(
        private readonly CommissionRepository $commissionRepository
    ) {
    }

    public function calculate(Commission $commission): float
    {
        $userCommissions = $this->commissionRepository->findByUserId($commission->getUserId());

        $segregatedByWeekNumber = [];
        foreach ($userCommissions as $userCommission) {
            if ($userCommission->getDate() <= $commission->getDate()) {
                $weekNumber = $userCommission->getDateWeekNumber();
                $segregatedByWeekNumber[$weekNumber][] = $userCommission;
            }
        }

        $week = $commission->getDateWeekNumber();

        if (!isset($segregatedByWeekNumber[$week])) {
            return self::FEE_FREE_OF_CHARGE_LIMIT;
        }

        $commissionsByWeek = $segregatedByWeekNumber[$week];

        if (count($commissionsByWeek[$week]) > 3) {
            return 0.0;
        }

        $limit = self::FEE_FREE_OF_CHARGE_LIMIT;
        /** @var Commission $commissionByWeek  */
        foreach ($commissionsByWeek as $commissionByWeek) {
            $limit -= $commissionByWeek->getAmount();
        }

        return $limit;
    }
}