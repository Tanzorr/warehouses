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

    public function handleGetRates(PaginationRequest $pagination): array
    {
        $validated = $this->validator->validate($pagination);

        return is_array($validated) ? ['errors' => $validated] : $this->rateService->getRates($validated->page, $validated->limit);
    }

    public function handleGetRate(PaginationRequest $pagination, string $name): array
    {
        $validated = $this->validator->validate($pagination);

        return is_array($validated) ? ['errors' => $validated] : $this->rateService->getRate($name, $validated->page, $validated->limit);
    }
}
