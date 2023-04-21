<?php

declare(strict_types=1);


namespace App\Application\Commission\Dto;

final class CommissionDto
{
    public string $date;
    public int $userId;
    public string $clientType;
    public string $operationType;
    public float $amount;
    public string $currency;
}