<?php

declare(strict_types=1);

namespace App\Domain\Commission\Service;

use App\Domain\Commission\Entity\Commission;

interface FeeCalculator
{
    public function calculate(Commission $commission): float;
    public function support(Commission $commission): bool;
}