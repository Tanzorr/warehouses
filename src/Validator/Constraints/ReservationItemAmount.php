<?php

// src/Validator/Constraints/ReservationItemAmount.php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS)]
class ReservationItemAmount extends Constraint
{
    public string $message = 'Недостатньо товарів на складі або вказано невалідну кількість ({{ value }} / доступно: {{ available }}).';

    public function validatedBy(): string
    {
        return static::class.'Validator';
    }

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}

