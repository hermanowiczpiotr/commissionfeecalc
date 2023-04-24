<?php


declare(strict_types=1);

namespace Unit\Domain\Service;

use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Enum\ClientType;
use App\Domain\Commission\Enum\OperationType;
use App\Domain\Commission\Repository\CommissionRepository;
use App\Domain\Commission\Service\CurrencyCalculator;
use App\Domain\Commission\Service\FreeWithdrawLimitCalculator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class FreeWithdrawLimitCalculatorTest extends TestCase
{
    private CommissionRepository|MockObject $commissionRepository;

    private CurrencyCalculator|MockObject $currencyCalculator;

    private FreeWithdrawLimitCalculator $freeWithdrawLimitCalculator;

    protected function setUp(): void
    {
        $this->commissionRepository =  $this->createMock(CommissionRepository::class);
        $this->currencyCalculator = $this->createMock(CurrencyCalculator::class);

        $this->freeWithdrawLimitCalculator = new FreeWithdrawLimitCalculator(
            $this->commissionRepository,
            $this->currencyCalculator
        );
    }

    public function testCalculationForReducedLimit(): void
    {
        $commission = Commission::create(
            '2022-04-24',
            1,
            ClientType::Private->value,
            OperationType::Withdraw->value,
            1000,
            'EUR',
        );

        $this->currencyCalculator
            ->expects($this::exactly(2))
            ->method('calculateRate')
            ->willReturn(200.00);

        $this->commissionRepository
            ->expects($this::once())
            ->method('findByUserId')
            ->with($commission->getUserId())
            ->willReturn([
                Commission::create(
                    '2021-04-24',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    1000,
                    'EUR',
                ),
                Commission::create(
                    '2021-08-25',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    200,
                    'EUR',
                ),
                Commission::create(
                    '2022-01-24',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    500,
                    'EUR',
                ),
                //next two should be run
                Commission::create(
                    '2022-04-22',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    200,
                    'EUR',
                ),
                Commission::create(
                    '2022-04-23',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    200,
                    'EUR',
                ),
            ]);

        $this->assertEquals(600, $this->freeWithdrawLimitCalculator->calculate($commission));
    }

    public function testCalculationForNotWithdrawnInProvidedWeek(): void
    {
        $commission = Commission::create(
            '2022-04-24',
            1,
            ClientType::Private->value,
            OperationType::Withdraw->value,
            1000,
            'EUR',
        );

        $this->currencyCalculator
            ->expects($this::never())
            ->method('calculateRate');

        $this->commissionRepository
            ->expects($this::once())
            ->method('findByUserId')
            ->with($commission->getUserId())
            ->willReturn([
                Commission::create(
                    '2021-04-24',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    1000,
                    'EUR',
                ),
                Commission::create(
                    '2021-08-25',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    200,
                    'EUR',
                ),
                Commission::create(
                    '2022-01-24',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    500,
                    'EUR',
                ),
                //next two should be run
                Commission::create(
                    '2022-05-22',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    200,
                    'EUR',
                ),
                Commission::create(
                    '2022-06-23',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    200,
                    'EUR',
                ),
            ]);

        $this->assertEquals(1000, $this->freeWithdrawLimitCalculator->calculate($commission));
    }

    public function testCalculationForUsedThreeWithdrawnInProvidedWeek(): void
    {
        $commission = Commission::create(
            '2022-04-24',
            1,
            ClientType::Private->value,
            OperationType::Withdraw->value,
            1000,
            'EUR',
        );

        $this->currencyCalculator
            ->expects($this::never())
            ->method('calculateRate');

        $this->commissionRepository
            ->expects($this::once())
            ->method('findByUserId')
            ->with($commission->getUserId())
            ->willReturn([
                Commission::create(
                    '2021-04-24',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    1000,
                    'EUR',
                ),
                Commission::create(
                    '2022-04-19',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    200,
                    'EUR',
                ),
                Commission::create(
                    '2022-04-20',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    500,
                    'EUR',
                ),
                //next two should be run
                Commission::create(
                    '2022-04-21',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    200,
                    'EUR',
                ),
                Commission::create(
                    '2022-04-22',
                    1,
                    ClientType::Private->value,
                    OperationType::Withdraw->value,
                    200,
                    'EUR',
                ),
            ]);

        $this->assertEquals(0.0, $this->freeWithdrawLimitCalculator->calculate($commission));
    }
}