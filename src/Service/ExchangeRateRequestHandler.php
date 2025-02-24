<?php

namespace App\Service;

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
        $pagination = new PaginationRequest(
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        $validated = $this->validator->validate($pagination);
        if (is_array($validated)) {
            return ['errors' => $validated];
        }

        return $this->rateService->getRates($validated->page, $validated->limit);
    }

    public function handleGetRate(Request $request, string $name): array
    {
        $pagination = new PaginationRequest(
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        $validated = $this->validator->validate($pagination);
        if (is_array($validated)) {
            return ['errors' => $validated];
        }

        return $this->rateService->getRate($name, $validated->page, $validated->limit);
    }
}
