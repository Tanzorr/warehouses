<?php

namespace App\Controller;

use App\Service\ExchangeRateService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints as Assert;
final class ExchangeRateController extends AbstractController
{
    /**
     * @throws \DateMalformedStringException|\Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    #[Route('/exchange/set-rates')]
    public function setRate(ExchangeRateService $rateService): JsonResponse
    {
        $rateService->updateRates();

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ExchangeRateController.php',
        ]);
    }

    #[Route('/exchange/get-rates', methods: ['GET'])]
    public function getRate(
        Request $request,
        ExchangeRateService $rateService,
        ValidatorInterface $validator
    ): JsonResponse
    {
        $input = [
            'page' => $request->query->get('page', 1),
            'limit' => $request->query->get('limit', 10),
        ];

        $errors = [];

        if (!is_numeric($input['page'])) {
            $errors[] = 'page: This value should be a number.';
        } else {
            $input['page'] = (int) $input['page'];
        }

        if (!is_numeric($input['limit'])) {
            $errors[] = 'limit: This value should be a number.';
        } else {
            $input['limit'] = (int) $input['limit'];
        }

        $constraints = new Assert\Collection([
            'page' => [new Assert\NotBlank(), new Assert\Type('integer'), new Assert\Positive()],
            'limit' => [new Assert\NotBlank(), new Assert\Type('integer'), new Assert\Positive()],
        ]);

        $violations = $validator->validate($input, $constraints);

        foreach ($violations as $violation) {
            $errors[] = $violation->getPropertyPath() . ': ' . $violation->getMessage();
        }

        if (count($errors) > 0) {
            return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($rateService->getRates($input['page'], $input['limit']));
    }
}