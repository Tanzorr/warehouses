<?php

namespace App\Command;

use App\Service\ExchangeRateService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateExchangeRatesCommand extends Command
{
    protected static $defaultName = 'update:exchange-rates';

    private ExchangeRateService $exchangeRateService;

    public function __construct(ExchangeRateService $exchangeRateService)
    {
        parent::__construct();
        $this->exchangeRateService = $exchangeRateService;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Update exchange rates from external API')
            ->addArgument(
                'timeout',
                InputArgument::OPTIONAL,
                'Timeout duration for the API request (in seconds)',
                30 // Значення за замовчуванням
            )->setName('app:update-exchange-rates')
            ->setDescription('Updates exchange rates from external API.');;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $timeout = (int)$input->getArgument('timeout');

        $output->writeln("Updating exchange rates with timeout: {$timeout} seconds");

        $this->exchangeRateService->updateRates($timeout);

        $output->writeln('Exchange rates updated successfully.');
        return Command::SUCCESS;
    }
}
