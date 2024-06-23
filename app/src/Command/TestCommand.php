<?php

declare(strict_types=1);

namespace App\Command;

use App\DTO\InputRowDto;
use App\Exception\BinResultException;
use App\Service\BinListService\BinListService;
use App\Service\RateService\RateService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(name: 'app:test', description: 'Calculates something for exchange.')]
class TestCommand extends Command
{
    public function __construct(
        private SerializerInterface $serializer,
        private BinListService $binListService,
        private RateService $rateService
    ) {
        parent::__construct(null);
    }

    protected function configure()
    {
        $this
            ->addOption('use-local-data', 'u', null, 'Use local data')
            ->addOption('input-no-exception', 'i', null, 'Use data from input file where all bin numbers are relevant');
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     * @throws BinResultException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $useLocalData = (bool)$input->getOption('use-local-data');
        $useInputNoException = (bool)$input->getOption('input-no-exception');

        $inputData = explode("\n", file_get_contents(__DIR__ . '/input.txt'));

        if ($useInputNoException) {
            $inputData = explode("\n", file_get_contents(__DIR__ . '/input_noException.txt'));
        }

        foreach ($inputData as $row) {
            if (empty($row)) {
                break;
            }

            $inputRowObject = $this->serializer->deserialize($row, InputRowDto::class, 'json');

            if ($useLocalData) {
                $rate = $this->rateService->getRateForCurrencyFromLocalData($inputRowObject);
            } else {
                $rate = $this->rateService->getRateForCurrency($inputRowObject);
            }

            if ($inputRowObject->getCurrency() === 'EUR' or $rate == 0) {
                $amntFixed = $inputRowObject->getAmount();
            }
            if ($inputRowObject->getCurrency() !== 'EUR' or $rate > 0) {
                $amntFixed = $inputRowObject->getAmount() / $rate;
            }

            if ($useLocalData) {
                $country = $this->binListService->getCountryFromBinListLocalData($inputRowObject);
            } else {
                $country = $this->binListService->getCountryFromBinList($inputRowObject);
            }

            echo $amntFixed * ($this->isEu($country) ? 0.01 : 0.02);
            print "\n";
        }

        return Command::SUCCESS;
    }

    protected function isEu($country): bool
    {
        $euCountriesArr = ['AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',];
        return in_array($country, $euCountriesArr);
    }
}
