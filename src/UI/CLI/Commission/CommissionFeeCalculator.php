<?php


declare(strict_types=1);

namespace App\UI\CLI\Commission;

use App\Application\Commission\Import\CurrencyRatesImporter;
use App\Application\Commission\Provider\CommissionsFeeProvider;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:commission:calculate'
)]
final class CommissionFeeCalculator extends Command
{
    public function __construct(
        string $name = null,
        private readonly CurrencyRatesImporter $currencyRatesImporter,
        private readonly CommissionsFeeProvider $commissionsFeeProvider,
    ) {
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'file name')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->currencyRatesImporter->import();

        $commissionsFees = $this->commissionsFeeProvider->getCommissionsFeesByProvidedCommissions(
            $input->getArgument('file')
        );

        foreach ($commissionsFees as $commissionFee) {
            $output->write(sprintf("%s\n", $commissionFee));
        }

        return Command::SUCCESS;
    }
}