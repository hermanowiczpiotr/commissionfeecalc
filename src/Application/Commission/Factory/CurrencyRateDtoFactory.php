<?php


declare(strict_types=1);

namespace App\Application\Commission\Factory;

use App\Application\Commission\Dto\CurrencyRateDto;

final class CurrencyRateDtoFactory
{
    /** @return CurrencyRateDto[] */
    public function createFromData(array $data): array
    {
        $currencyDtos = [];
        foreach ($data as $currencyCode => $currencyRate) {
            $currencyDtos[] = new CurrencyRateDto($currencyCode, $currencyRate);
        }

        return $currencyDtos;
    }
}