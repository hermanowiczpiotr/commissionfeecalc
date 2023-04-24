<?php


declare(strict_types=1);

namespace Unit\Application\Commission\Handler;

use App\Application\Commission\Handler\CommissionFeeCalculatorHandler;
use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Enum\ClientType;
use App\Domain\Commission\Enum\OperationType;
use App\Domain\Commission\Exception\FeeCalculatorNotFoundException;
use App\Domain\Commission\Service\FeeCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CommissionFeeCalculatorHandlerTest extends TestCase
{
    private FeeCalculator|MockObject $feeCalculator1;
    private FeeCalculator|MockObject $feeCalculator2;

    protected function setUp(): void
    {
       $this->feeCalculator1 = $this->createMock(FeeCalculator::class);
       $this->feeCalculator2 = $this->createMock(FeeCalculator::class);

    }

    public function testCalculateFee()
    {
        $commission = Commission::create(
            '2023-04-24',
            1,
            ClientType::Business->value,
            OperationType::Deposit->value,
            1000,
            'EUR',
        );

        $this->feeCalculator1->expects($this->once())
            ->method('support')
            ->with($commission)
            ->willReturn(false);

        $this->feeCalculator2->expects($this->once())
            ->method('support')
            ->with($commission)
            ->willReturn(true);

        $this->feeCalculator2->expects($this->once())
            ->method('calculate')
            ->with($commission)
            ->willReturn(1.00);

        $handler = new CommissionFeeCalculatorHandler([$this->feeCalculator1, $this->feeCalculator2]);
        $fee = $handler->calculateFee($commission);

        $this->assertEquals(1.00, $fee);
    }

    public function testCalculateFeeNotFound()
    {
        $commission = Commission::create(
            '2023-04-24',
            1,
            ClientType::Business->value,
            OperationType::Deposit->value,
            1000,
            'EUR',
        );

        $this->feeCalculator1->expects($this->once())
            ->method('support')
            ->with($commission)
            ->willReturn(false);

        $handler = new CommissionFeeCalculatorHandler([$this->feeCalculator1]);

        $this->expectException(FeeCalculatorNotFoundException::class);
        $handler->calculateFee($commission);
    }
}