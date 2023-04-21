<?php


declare(strict_types=1);

namespace App\Domain\Commission\Service;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Enum\OperationType;
use App\Domain\Commission\Repository\CommissionRepository;

class FreeWithdrawLimitCalculator
{
    private const FEE_FREE_OF_CHARGE_LIMIT = 1000.00;

    private const FIRST_WEEK_OF_YEAR = 01;

    public function __construct(
        private readonly CommissionRepository $commissionRepository,
        private readonly CurrencyCalculator $currencyCalculator
    ) {
    }

    public function calculate(Commission $commission): float
    {
        $userCommissions = $this->commissionRepository->findByUserId($commission->getUserId());

        $segregatedByWeekNumber = [];
        foreach ($userCommissions as $userCommission) {

            //skipping duplicated
            if ($userCommission->getDate() === $commission->getDate() &&
                $userCommission->getUserId() === $commission->getUserId()) {
                continue;
            }

            //only for deposits
            if ($userCommission->getOperationType()->equal(OperationType::Deposit)) {
                continue;
            }

            //for case if first week of year started in previous year
            if ($commission->getDateWeekNumber() === $userCommission->getDateWeekNumber() &&
                $commission->getDateYearNumber() !== $userCommission->getDateYearNumber() &&
                $userCommission->getDateWeekNumber() != self::FIRST_WEEK_OF_YEAR
            ) {
                continue;
            }

            if ($userCommission->getDate() <= $commission->getDate()) {
                $weekNumber = $userCommission->getDateWeekNumber();
                $segregatedByWeekNumber[$weekNumber][] = $userCommission;
            }
        }

        $week = $commission->getDateWeekNumber();

        if (!isset($segregatedByWeekNumber[$week])) {
            return self::FEE_FREE_OF_CHARGE_LIMIT;
        }

        if (count($segregatedByWeekNumber[$week]) > 3) {
            return 0.0;
        }

        $commissionsByWeek = $segregatedByWeekNumber[$week];

        $limit = self::FEE_FREE_OF_CHARGE_LIMIT;

        /** @var Commission $commissionByWeek  */
        foreach ($commissionsByWeek as $commissionByWeek) {
            $limit -= $this->currencyCalculator->calculateRate($commissionByWeek);
        }

        return max($limit, 0);
    }
}