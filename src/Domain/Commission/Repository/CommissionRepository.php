<?php

declare(strict_types=1);

namespace App\Domain\Commission\Repository;

use App\Domain\Commission\Entity\Commission;

interface CommissionRepository
{
    public function add(Commission $commission): void;

    /** @return Commission[] */
    public function findAll(): array;

    /** @return Commission[] */
    public function findByUserId(int $userId): array;
}