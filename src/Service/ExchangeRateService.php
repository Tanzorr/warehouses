<?php
namespace App\Service;

use App\Entity\ExchangeRate;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private string $apiUrl = 'https://blockchain.info/ticker';

    public function __construct(
        private readonly HttpClientInterface    $client,
        private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     *
     * @throws \DateMalformedStringException
     */
    public function updateRates(int $timeout = 30): void
    {
        $response = $this->client->request('GET', $this->apiUrl, [
            'timeout' => $timeout,
        ]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('API returned an error status: ' . $response->getStatusCode());
        }

        $data = $response->toArray();

        $currencies = ['USD', 'EUR','PLN'];

        foreach ($data as $currencyCode => $info) {
            if(in_array($info['symbol'], $currencies)){
                $currencyPair = 'BTC/' . $info['symbol'];

                $rate = (float)$info['last'];
                $recordedAt = new \DateTimeImmutable('now', new \DateTimeZone('UTC'));

                $exchangeRate = new ExchangeRate();
                $exchangeRate->setCurrencyPair($currencyPair)
                    ->setRate($rate)
                    ->setRecordedAt($recordedAt);

                $this->entityManager->persist($exchangeRate);
            }

        }

        $this->entityManager->flush();
    }
}