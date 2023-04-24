<?php


declare(strict_types=1);

namespace App\Domain\Commission\Exception;

use App\Domain\Commission\Entity\Commission;

class FeeCalculatorNotFoundException extends \Exception
{
    public static function createForCommission(Commission $commission): self
    {
        return new self(sprintf(
            "Handler for client_type: %s and operation_type: %s not found",
            $commission->getClientType()->value,
            $commission->getOperationType()->value
        ));
    }
}