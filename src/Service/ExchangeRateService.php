<?php
namespace App\Service;

use App\Entity\ExchangeRate;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ExchangeRateService
{
    private array $supportedCurrenciesExploded;

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly ExchangeRateRepository $rateRepository,
        private readonly string $apiUrl,
        private readonly string $supportedCurrencies
    ) {
        $this->supportedCurrenciesExploded = explode(',', $this->supportedCurrencies);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function updateRates(int $timeout = 30): void
    {
        $data = $this->fetchExchangeRates($timeout);

        foreach ($data as $info) {
            if ($this->isSupportedCurrency($info['symbol'])) {
                $this->persistExchangeRate($info['symbol'], (float)$info['last_trade_price']);
            }
        }

        $this->entityManager->flush();
    }

    public function getRates(int $page = 1, int $limit = 10): array
    {
        return $this->paginateRates($page, $limit);
    }

    public function getRate(string $name, int $page = 1, int $limit = 10): array
    {
        return $this->paginateRates($page, $limit, $name);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function fetchExchangeRates(int $timeout): array
    {
        $response = $this->client->request('GET', $this->apiUrl, ['timeout' => $timeout]);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('API error: ' . $response->getStatusCode());
        }

        return $response->toArray();
    }

    private function isSupportedCurrency(string $currency): bool
    {
        return in_array($currency, $this->supportedCurrenciesExploded, true);
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function persistExchangeRate(string $currency, float $rate): void
    {
        $exchangeRate = $this->createExchangeRate($currency, $rate);
        $this->entityManager->persist($exchangeRate);
    }

    /**
     * @throws \DateMalformedStringException
     */
    private function createExchangeRate(string $currency, float $rate): ExchangeRate
    {
        return (new ExchangeRate())
            ->setCurrencyPair($currency)
            ->setRate($rate)
            ->setRecordedAt(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
    }

    private function paginateRates(int $page, int $limit, string $currencyPair = null): array
    {
        $queryBuilder = $this->rateRepository->createQueryBuilder('r')
            ->orderBy('r.recordedAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        if ($currencyPair) {
            $queryBuilder->where('r.currencyPair = :currencyPair')
                ->setParameter('currencyPair', $currencyPair);
        }

        $paginator = new Paginator($queryBuilder, true);
        $totalItems = count($paginator);
        $items = iterator_to_array($paginator);

        return [
            'total' => $totalItems,
            'page' => $page,
            'items_per_page' => $limit,
            'total_pages' => ceil($totalItems / $limit),
            'data' => $this->formatRates($items),
        ];
    }

    private function formatRates(array $rates): array
    {
        $rateResponse = [];

        foreach ($rates as $rate) {
            $currencyPair = (string) $rate->getCurrencyPair();
            $recordedAt = $rate->getRecordedAt()->format('Y-m-d H:i:s');
            $rateResponse[$currencyPair][$recordedAt] = $rate->getRate();
        }

        return $rateResponse;
    }
}