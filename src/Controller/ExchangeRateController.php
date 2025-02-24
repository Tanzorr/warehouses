<?php

namespace App\Controller;

use App\DTO\PaginationRequest;
use App\Service\ExchangeRateService;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

final class ExchangeRateController extends AbstractController
{
    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/exchange/set-rates')]
    public function setRate(ExchangeRateService $rateService): JsonResponse
    {
        $rateService->updateRates();
        return $this->json(['message' => 'Rates updated successfully']);
    }

    #[Route('/exchange/get-rates', methods: ['GET'])]
    public function getRates(Request $request, ExchangeRateService $rateService, ValidatorInterface $validator): JsonResponse
    {
        $pagination = new PaginationRequest(
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        $validator->validate($pagination);
        return $this->json($rateService->getRates($pagination->page, $pagination->limit));
    }

    #[Route('/exchange/get-rates/{name}', methods: ['GET'])]
    public function getRate(string $name, Request $request, ExchangeRateService $rateService, ValidatorInterface $validator): JsonResponse
    {
        $pagination = new PaginationRequest(
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        $validator->validate($pagination);
        return $this->json($rateService->getRate($name, $pagination->page, $pagination->limit));
    }
}