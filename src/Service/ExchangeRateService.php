<?php
namespace App\Service;

use App\Entity\ExchangeRate;
use App\Repository\ExchangeRateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;

class ExchangeRateService
{
    private array $supportedCurrenciesExploded;

    public function __construct(
        private readonly HttpClientInterface    $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly ExchangeRateRepository $rateRepository,
        private readonly string                 $apiUrl,
        private readonly string                 $supportedCurrencies
    ) {
        $this->supportedCurrenciesExploded = explode(',', $this->supportedCurrencies);
    }

    /**
     * Updates exchange rates by fetching data from an external API.
     *
     * @param int $timeout API request timeout in seconds (default: 30)
     * @throws TransportExceptionInterface
     * @throws \DateMalformedStringException
     */
    public function updateRates(int $timeout = 30): void
    {
        $data = $this->fetchExchangeRates($timeout);

        foreach ($data as $info) {
            if (!$this->isSupportedCurrency($info['symbol'])) {
                continue;
            }

            $exchangeRate = $this->createExchangeRate($info['symbol'], (float)$info['last_trade_price']);
            $this->entityManager->persist($exchangeRate);
        }

        $this->entityManager->flush();
    }

    /**
     * Retrieves all stored exchange rates.
     *
     * @return array Structured exchange rate data
     */
    public function getRates(int $page = 1, int $limit = 10): array
    {
        $queryBuilder = $this->rateRepository->createQueryBuilder('r')
            ->orderBy('r.recordedAt', 'DESC')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

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

    /**
     * Fetches exchange rates from the external API.
     *
     * @param int $timeout API request timeout in seconds
     * @return array Decoded API response
     * @throws TransportExceptionInterface
     */
    private function fetchExchangeRates(int $timeout): array
    {
        $response = $this->client->request('GET', $this->apiUrl, ['timeout' => $timeout]);

        if ($response->getStatusCode() !== 200) {
            throw new \RuntimeException('API error: ' . $response->getStatusCode());
        }

        return $response->toArray();
    }

    /**
     * Checks if the given currency is supported.
     *
     * @param string $currency Currency code (e.g., USD, EUR)
     * @return bool True if currency is supported, false otherwise
     */
    private function isSupportedCurrency(string $currency): bool
    {
        return in_array($currency, $this->supportedCurrenciesExploded, true);
    }

    /**
     * Creates an ExchangeRate entity with the given data.
     *
     * @param string $currency Currency code
     * @param float $rate Exchange rate value
     * @return ExchangeRate The created exchange rate entity
     * @throws \DateMalformedStringException
     */
    private function createExchangeRate(string $currency, float $rate): ExchangeRate
    {
        return (new ExchangeRate())
            ->setCurrencyPair($currency)
            ->setRate($rate)
            ->setRecordedAt(new \DateTimeImmutable('now', new \DateTimeZone('UTC')));
    }

    /**
     * Formats exchange rate data for structured output.
     *
     * @param ExchangeRate[] $rates List of exchange rate entities
     * @return array Formatted exchange rate data
     */
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