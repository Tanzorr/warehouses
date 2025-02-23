<?php

namespace App\Controller;

use App\DTO\PaginationRequest;
use App\Service\ExchangeRateService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;
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

        $validated = $validator->validate($pagination);
        return $this->json($rateService->getRates($pagination->page, $pagination->limit));
    }

    #[Route('/exchange/get-rates/{name}', methods: ['GET'])]
    public function getRate(string $name, Request $request, ExchangeRateService $rateService, ValidatorInterface $validator): JsonResponse
    {
        $input = $this->validateRequest($request, $validator);
        if (isset($input['errors'])) {
            return $this->json(['errors' => $input['errors']], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($rateService->getRate($name, $input['page'], $input['limit']));
    }

    private function validateRequest(Request $request, ValidatorInterface $validator): array
    {
        $input = [
            'page' => $request->query->getInt('page', 1),
            'limit' => $request->query->getInt('limit', 10),
        ];

        $constraints = new Assert\Collection([
            'page' => [new Assert\NotBlank(), new Assert\Type('integer'), new Assert\Positive()],
            'limit' => [new Assert\NotBlank(), new Assert\Type('integer'), new Assert\Positive()],
        ]);

        $violations = $validator->validate($input, $constraints);
        if (count($violations) > 0) {
            $errors = array_map(fn($violation) => $violation->getPropertyPath() . ': ' . $violation->getMessage(), iterator_to_array($violations));
            return ['errors' => $errors];
        }

        return $input;
    }
}