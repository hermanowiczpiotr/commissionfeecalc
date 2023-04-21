<?php

declare(strict_types=1);

namespace App\Application\Commission\Factory;

use App\Application\Commission\Dto\CurrencyRateDto;
use Symfony\Component\Serializer\SerializerInterface;

final class CurrencyRateDtoFactory
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    /** @return CurrencyRateDto[] */
    public function createFromData(string $data): array
    {
        /** @var CurrencyRateDto[] $employeeDtos */
        return $this->serializer->deserialize(
            $data,
            CurrencyRateDto::class .  '[]',
            'json'
        );
    }
}