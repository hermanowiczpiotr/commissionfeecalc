<?php


declare(strict_types=1);

namespace Unit\Domain\Service;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Enum\ClientType;
use App\Domain\Commission\Enum\OperationType;
use App\Domain\Commission\Service\CurrencyCalculator;
use App\Domain\Commission\Service\FreeWithdrawLimitCalculator;
use App\Domain\Commission\Service\PrivateDepositFeeCalculator;
use App\Domain\Commission\Service\PrivateWithdrawFeeCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PrivateWithdrawFeeCalculatorTest extends TestCase
{
    private CurrencyCalculator|MockObject $currencyCalculator;
    private FreeWithdrawLimitCalculator|MockObject $freeWithdrawLimitCalculator;

    private PrivateWithdrawFeeCalculator $privateWithdrawFeeCalculator;

    public function setUp(): void
    {
        $this->currencyCalculator = $this->createMock(CurrencyCalculator::class);
        $this->freeWithdrawLimitCalculator = $this->createMock(FreeWithdrawLimitCalculator::class);
        $this->privateWithdrawFeeCalculator = new PrivateWithdrawFeeCalculator(
            $this->freeWithdrawLimitCalculator,
            $this->currencyCalculator
        );
    }

    public function testCalculateWithNoLimit(): void
    {
        $commission = Commission::create(
            '2023-04-24',
            1,
            ClientType::Private->value,
            OperationType::Deposit->value,
            1000,
            'EUR',
        );

        $this->currencyCalculator->expects($this->once())
            ->method('calculateRate')
            ->with($commission)
            ->willReturn(1000.00);

        $this->freeWithdrawLimitCalculator->expects($this->once())
            ->method('calculate')
            ->with($commission)
            ->willReturn(1000.00);

        $fee = $this->privateWithdrawFeeCalculator->calculate($commission);

        $this->assertEquals(0.0, $fee);
    }

    public function testCalculateWithLimit(): void
    {
        $commission = Commission::create(
            '2023-04-24',
            1,
            ClientType::Private->value,
            OperationType::Deposit->value,
            1000,
            'EUR',
        );

        $this->currencyCalculator->expects($this->once())
            ->method('calculateRate')
            ->with($commission)
            ->willReturn(1200.00);

        $this->freeWithdrawLimitCalculator->expects($this->once())
            ->method('calculate')
            ->with($commission)
            ->willReturn(0.0);

        $fee = $this->privateWithdrawFeeCalculator->calculate($commission);

        $this->assertEquals(3.6, $fee);
    }

    public function testSupport(): void
    {
        $this->assertTrue(
            $this->privateWithdrawFeeCalculator->support(
                Commission::create(
                    '2023-04-24',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    1000,
                    'EUR',
                )
            ));

        $this->assertFalse(
            $this->privateWithdrawFeeCalculator->support(
                Commission::create(
                    '2023-04-24',
                    1,
                    ClientType::Business->value,
                    OperationType::Deposit->value,
                    1000,
                    'EUR',
                )
            ));

        $this->assertFalse(
            $this->privateWithdrawFeeCalculator->support(
                Commission::create(
                    '2023-04-24',
                    1,
                    ClientType::Private->value,
                    OperationType::Deposit->value,
                    1000,
                    'EUR',
                )
            ));
    }
}