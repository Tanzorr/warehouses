<?php

namespace App\Service;

use App\DTO\PaginationRequest;
use Symfony\Component\HttpFoundation\Request;

class ExchangeRateRequestHandler
{
    public function __construct(
        private readonly ExchangeRateService $rateService,
        private readonly RequestValidator $validator
    ) {}

    public function handleGetRates(Request $request): array
    {
        return $this->processRequest($request, fn($pagination) =>
        $this->rateService->getRates($pagination->page, $pagination->limit)
        );
    }

    public function handleGetRate(Request $request, string $name): array
    {
        return $this->processRequest($request, fn($pagination) =>
        $this->rateService->getRate($name, $pagination->page, $pagination->limit)
        );
    }

    private function processRequest(Request $request, callable $callback): array
    {
        $pagination = self::createPaginationRequest($request);
        $validated = $this->validator->validate($pagination);

        return is_array($validated) ? ['errors' => $validated] : $callback($validated);
    }

    private static function createPaginationRequest(Request $request): PaginationRequest
    {
        return new PaginationRequest(
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );
    }
}
