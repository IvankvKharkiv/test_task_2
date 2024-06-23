<?php

namespace App\Service\RateService;

use App\DTO\InputRowDto;
use App\Exception\BinResultException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class RateService
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }

    /**
     * @param InputRowDto $inputRowObject
     * @return float
     * @throws BinResultException
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getRateForCurrency(InputRowDto $inputRowObject): float
    {
        $response = $this->httpClient->request(
            \Symfony\Component\HttpFoundation\Request::METHOD_GET,
            sprintf('http://api.exchangeratesapi.io/v1/latest?access_key=%s&format=1', '0b9ab5147fa06aab3cdb30e89c5ffdfc'), //this must be somewhere in the environment
        );

        if ($response->getStatusCode() !== 200) {
            throw new BinResultException('Could not get exchange rates from API');
        }

        $rateJson = json_decode($response->getContent(), true);

        if (!isset($rateJson['rates'][$inputRowObject->getCurrency()])) {
            throw new BinResultException('Could not get exchange rate wrong json structure.');
        }

        $rate = $rateJson['rates'][$inputRowObject->getCurrency()];

        return $rate;
    }

    /**
     * @param InputRowDto $inputRowObject
     * @return float
     */
    public function getRateForCurrencyFromLocalData(InputRowDto $inputRowObject): float
    {
        return @json_decode(file_get_contents(__DIR__ . '/rates.json'), true)['rates'][$inputRowObject->getCurrency()];
    }

}
