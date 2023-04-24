<?php


declare(strict_types=1);

namespace App\Domain\Commission\Entity;

use App\Domain\Commission\Enum\ClientType;
use App\Domain\Commission\Enum\CurrencyType;
use App\Domain\Commission\Enum\OperationType;
use DateTime;

final class Commission
{
    public function __construct(
        private DateTime $date,
        private int $userId,
        private ClientType $clientType,
        private OperationType $operationType,
        private float $amount,
        private CurrencyType $currencyType
    ) {
    }

    public static function create(
        string $date,
        int $userId,
        string $clientType,
        string $operationType,
        float $amount,
        string $currencyType
    ): self {
        return new Commission(
            new DateTime($date),
            $userId,
            ClientType::from($clientType),
            OperationType::from($operationType),
            $amount,
            CurrencyType::from($currencyType)
        );
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getClientType(): ClientType
    {
        return $this->clientType;
    }

    public function getOperationType(): OperationType
    {
        return $this->operationType;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getDate(): DateTime
    {
        return $this->date;
    }

    public function getDateWeekNumber(): int
    {
        return (int) $this->date->format('W');
    }

    public function getDateYearNumber(): int
    {
        return (int) $this->date->format('Y');
    }

    public function getCurrencyType(): CurrencyType
    {
        return $this->currencyType;
    }

    public function getDateMonthNumber(): int
    {
        return (int) $this->date->format('M');

    }
}