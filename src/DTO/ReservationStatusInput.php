<?php

namespace App\DTO;


use Symfony\Component\Validator\Constraints as Assert;
class ReservationStatusInput
{
    #[Assert\NotBlank]
    #[Assert\Choice(
        choices: ['active', 'pending', 'canceled'],
        message: 'Status must be one of: active, pending, canceled.'
    )]
    public string $status;
}
