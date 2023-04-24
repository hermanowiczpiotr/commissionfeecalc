<?php


declare(strict_types=1);

namespace Unit\Application\Commission\Factory;

use App\Application\Commission\Dto\CurrencyRateDto;
use App\Application\Commission\Factory\CurrencyRateDtoFactory;
use PHPUnit\Framework\TestCase;

class CurrencyRateDtoFactoryTest extends TestCase
{
    public function testCreateFromData(): void
    {
        $data = [
            'USD' => 1.0,
            'EUR' => 0.9,
            'GBP' => 0.8,
        ];

        $factory = new CurrencyRateDtoFactory();
        $dtos = $factory->createFromData($data);

        $this->assertCount(3, $dtos);

        $this->assertInstanceOf(CurrencyRateDto::class, $dtos[0]);
        $this->assertEquals('USD', $dtos[0]->code);
        $this->assertEquals(1.0, $dtos[0]->rate);

        $this->assertInstanceOf(CurrencyRateDto::class, $dtos[1]);
        $this->assertEquals('EUR', $dtos[1]->code);
        $this->assertEquals(0.9, $dtos[1]->rate);

        $this->assertInstanceOf(CurrencyRateDto::class, $dtos[2]);
        $this->assertEquals('GBP', $dtos[2]->code);
        $this->assertEquals(0.8, $dtos[2]->rate);
    }
}