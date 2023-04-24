<?php


declare(strict_types=1);

namespace App\Domain\Commission\Enum;

enum CurrencyType: string {
    case EUR = 'EUR';
    case USD = 'USD';
    case JPY = 'JPY';
}