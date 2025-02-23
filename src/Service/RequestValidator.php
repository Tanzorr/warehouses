<?php

namespace App\Service;


use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValidator
{
    public function __construct(private ValidatorInterface $validator) {}

    public function validate(object $dto): object|array
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            return array_map(
                fn($violation) => $violation->getPropertyPath() . ': ' . $violation->getMessage(),
                iterator_to_array($violations)
            );
        }

        return $dto;
    }
}