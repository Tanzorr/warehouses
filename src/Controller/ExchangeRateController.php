<?php

namespace App\Controller;

use App\DTO\PaginationRequest;
use App\Service\ExchangeRateRequestHandler;
use App\Service\ExchangeRateService;
use App\Service\RequestValidator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;
final class ExchangeRateController extends AbstractController
{

    public function __construct(
        private  ExchangeRateService $rateService,
        private readonly ExchangeRateRequestHandler $requestHandler,
        private readonly SerializerInterface $serializer
    ) {}

    /**
     * @throws \DateMalformedStringException
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/exchange/set-rates')]
    public function setRate(): JsonResponse
    {
        $this->rateService->updateRates();
        return $this->json(['message' => 'Rates updated successfully']);
    }

    #[Route('/exchange/get-rates', methods: ['GET'])]
    public function getRates(Request $request): JsonResponse
    {
        $data = $this->requestHandler->handleGetRates(new PaginationRequest(
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        ));
        return isset($data['errors'])
            ? $this->json($data, Response::HTTP_BAD_REQUEST)
            : $this->json($data);
    }

    #[Route('/exchange/get-rates/{name}', methods: ['GET'])]
    public function getRate(string $name, Request $request): JsonResponse
    {
        $data = $this->requestHandler->handleGetRate(new PaginationRequest(
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        ), $name);
        return isset($data['errors'])
            ? $this->json($data, Response::HTTP_BAD_REQUEST)
            : $this->json($data);
    }
}