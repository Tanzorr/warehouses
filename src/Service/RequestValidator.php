<?php
namespace App\Service;

use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class RequestValidator
{
    public function __construct(private readonly ValidatorInterface $validator) {}

    public function validate(object $dto): object|array
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            return $this->formatViolations($violations);
        }

        return $dto;
    }

    private function formatViolations(ConstraintViolationListInterface $violations): array
    {
        return array_map(
            fn($violation) => $violation->getPropertyPath() . ': ' . $violation->getMessage(),
            iterator_to_array($violations)
        );
    }
}
