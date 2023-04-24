<?php


declare(strict_types=1);

namespace Unit\Domain\Service;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Enum\ClientType;
use App\Domain\Commission\Enum\OperationType;
use App\Domain\Commission\Service\BusinessDepositFeeCalculator;
use App\Domain\Commission\Service\BusinessWithdrawFeeCalculator;
use App\Domain\Commission\Service\CurrencyCalculator;
use App\Domain\Commission\Service\FreeWithdrawLimitCalculator;
use App\Domain\Commission\Service\PrivateDepositFeeCalculator;
use App\Domain\Commission\Service\PrivateWithdrawFeeCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class BusinessWithdrawFeeCalculatorTest extends TestCase
{
    private CurrencyCalculator|MockObject $currencyCalculator;
    private BusinessWithdrawFeeCalculator $businessWithdrawFeeCalculator;

    public function setUp(): void
    {
        $this->currencyCalculator = $this->createMock(CurrencyCalculator::class);
        $this->businessWithdrawFeeCalculator = new BusinessWithdrawFeeCalculator(
            $this->currencyCalculator
        );
    }

    public function testCalculate(): void
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


        $fee = $this->businessWithdrawFeeCalculator->calculate($commission);

        $this->assertEquals(6.0, $fee);
    }

    public function testSupport(): void
    {
        $this->assertTrue(
            $this->businessWithdrawFeeCalculator->support(
                Commission::create(
                    '2023-04-24',
                    1,
                    ClientType::Business->value,
                    OperationType::Withdraw->value,
                    1000,
                    'EUR',
                )
            ));

        $this->assertFalse(
            $this->businessWithdrawFeeCalculator->support(
                Commission::create(
                    '2023-04-24',
                    1,
                    ClientType::Private->value,
                    OperationType::Deposit->value,
                    1000,
                    'EUR',
                )
            ));

        $this->assertFalse(
            $this->businessWithdrawFeeCalculator->support(
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