<?php
namespace App\Service;

use App\Entity\ExchangeRate;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ExchangeRateService
{
    private string $apiUrl = 'https://blockchain.info/ticker';

    public function __construct(
        private readonly HttpClientInterface    $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly ExchangeRateRepository $rateRepository
    )
    {
    }

    /**
     *
     * @throws \DateMalformedStringException
     * @throws TransportExceptionInterface
     */
    public function updateRates(int $timeout = 30): void
    {
        $response = $this->client->request('GET', $this->apiUrl, ['timeout' => $timeout]);

        if ($response->getStatusCode() !== 200) {
            throw new \Exception('API returned an error status: ' . $response->getStatusCode());
        }

        $data = $response->toArray();
        $currencies = ['USD', 'EUR', 'PLN'];

        foreach ($data as $info) {
            if (in_array($info['symbol'], $currencies)) {
                $exchangeRate = (new ExchangeRate())
                    ->setCurrencyPair('BTC/' . $info['symbol'])
                    ->setRate((float)$info['last'])
                    ->setRecordedAt(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));

                $this->entityManager->persist($exchangeRate);
            }
        }

        $this->entityManager->flush();
    }

    public function getRates(): array
    {
        $rates = $this->rateRepository->findAll();
        $rateResponse = [];

        foreach ($rates as $rate) {
            $currencyPair = (string) $rate->getCurrencyPair();
            $recordedAt = $rate->getRecordedAt()->format('Y-m-d H:i:s');
            $rateResponse[$currencyPair][$recordedAt] = $rate->getRate();
        }

        return $rateResponse;
    }
}