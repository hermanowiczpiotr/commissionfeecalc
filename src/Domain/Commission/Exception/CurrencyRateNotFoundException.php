<?php

declare(strict_types=1);

namespace App\Domain\Commission\Exception;

use App\Domain\Commission\Enum\CurrencyType;

class CurrencyRateNotFoundException extends \Exception
{
    public static function createForCurrencyType(CurrencyType $currencyType): self
    {
        return new self(
            sprintf(
                "CurrencyRate for  currency_type: %s not found",
                $currencyType->value
            )
        );
    }
}