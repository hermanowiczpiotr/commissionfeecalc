<?php


declare(strict_types=1);

namespace App\Application\Commission\Provider;

use App\Application\Commission\Factory\CommissionsDtoFactory;
use App\Application\Commission\Handler\CommissionFeeCalculatorHandler;
use App\Domain\Commission\Entity\Commission;
use App\Domain\Commission\Exception\FeeCalculatorNotFoundException;
use App\Domain\Commission\Repository\CommissionRepository;
use App\Infrastructure\FileLoader\LoadDataFromFile;

final class CommissionsFeeProvider
{
    public function __construct(
        private readonly LoadDataFromFile $dataLoader,
        private readonly CommissionRepository $commissionRepository,
        private readonly CommissionFeeCalculatorHandler $commissionFeeCalculatorHandler,
        private readonly CommissionsDtoFactory $commissionsDtoFactory
    ) {
    }

    public function getCommissionsFeesByProvidedCommissions(string $commissionsFileName): array
    {
        $commissionsData = $this->dataLoader->loadByFilename($commissionsFileName);

        $commissionsDto = $this->commissionsDtoFactory->createFromDataFile($commissionsData);

        foreach ($commissionsDto as $commissionDto) {
            $this->commissionRepository->add(
                Commission::create(
                    $commissionDto->date,
                    $commissionDto->userId,
                    $commissionDto->clientType,
                    $commissionDto->operationType,
                    $commissionDto->amount,
                    $commissionDto->currency
                )
            );
        }

        $commissions = $this->commissionRepository->findAll();

        $fees = [];
        foreach ($commissions as $commission) {
            try {
                $fees[] = (string) $this->commissionFeeCalculatorHandler->calculateFee($commission);
            } catch (FeeCalculatorNotFoundException $exception) {
                $fees[] =  $exception->getMessage();
            }
        }

        return $fees;
    }
}