<?php


declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Repository\CommissionRepository;

final class CommissionMemoryRepository implements CommissionRepository
{
    /** @var Commission[]  */
    private array $commissions = [];

    public function add(Commission $commission): void
    {
        $this->commissions[] = $commission;
    }

    public function findAll(): array
    {
        return $this->commissions;
    }

    public function findByUserId(int $userId): array
    {
        $result = [];
        foreach ($this->commissions as $commission) {
            if ($commission->getUserId() === $userId) {
                $result[] = $commission;
            }
        }

        return $result;
    }
}