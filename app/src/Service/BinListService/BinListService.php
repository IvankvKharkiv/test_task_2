<?php

namespace App\Service\BinListService;

use App\DTO\InputRowDto;
use App\Exception\BinResultException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BinListService
{
    public function __construct(
        private HttpClientInterface $httpClient,
    ) {
    }


    public function getCountryFromBinList(InputRowDto $inputRowObject): string
    {
        $response = $this->httpClient->request(
            \Symfony\Component\HttpFoundation\Request::METHOD_GET,
            sprintf('https://lookup.binlist.net/%s', $inputRowObject->getBin()),
        );

        if ($response->getStatusCode() !== 200) {
            throw new BinResultException('Could not get lookup response');
        }

        $binList = json_decode($response->getContent());

        if (!isset($binList->country->alpha2)) {
            throw new BinResultException('Could not get lookup info. Response does not contain alpha2');
        }

        return $binList->country->alpha2;
    }

    public function getCountryFromBinListLocalData(InputRowDto $inputRowObject): string
    {
        $binList = file_get_contents(__DIR__ . sprintf('/binResults_%s.json', $inputRowObject->getBin()));
        $binList = json_decode($binList);

        if (!isset($binList->country->alpha2)) {
            throw new BinResultException('Could not get lookup info. Response does not contain alpha2');
        }

        return $binList->country->alpha2;
    }

}
