<?php


declare(strict_types=1);

namespace App\Application\Commission\Factory;

use App\Application\Commission\Dto\CommissionDto;
use Symfony\Component\Serializer\SerializerInterface;

class CommissionsDtoFactory
{
    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    public function createFromDataFile(string $data): array
    {
        /** @var CommissionDto[] $employeeDtos */
        return $this->serializer->deserialize(
            $data,
            CommissionDto::class .  '[]',
            'csv'
        );
    }
}