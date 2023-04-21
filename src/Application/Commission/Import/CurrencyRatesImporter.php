<?php


declare(strict_types=1);

namespace App\Application\Commission\Import;

use App\Application\Commission\Factory\CurrencyRateDtoFactory;
use App\Domain\Commission\Entity\CurrencyRate;
use App\Domain\Commission\Repository\CurrencyRateRepository;
use App\Infrastructure\FileLoader\LoadDataFromFile;

class CurrencyRatesImporter
{
    private const FILENAME = 'rates.json';

    public function __construct(
        private readonly LoadDataFromFile $loadDataFromFile,
        private readonly CurrencyRateDtoFactory $currencyRateDtoFactory,
        private readonly CurrencyRateRepository $currencyRateRepository
    ) {
    }

    public function import(): void
    {
        $data = $this->loadDataFromFile->loadByFilename(self::FILENAME);
        $currencyRateDtos = $this->currencyRateDtoFactory->createFromData(json_decode($data, true));

        foreach ($currencyRateDtos as $currencyRateDto) {
            $this->currencyRateRepository->add(
                CurrencyRate::create(
                    $currencyRateDto->code,
                    $currencyRateDto->rate
                )
            );
        }
    }
}