<?php


declare(strict_types=1);

namespace App\Domain\Commission\Enum;

enum OperationType: string {
    case Deposit = 'deposit';
    case Withdraw = 'withdraw';

    public function equal(OperationType $operationType): bool
    {
        return $this === $operationType;
    }
}