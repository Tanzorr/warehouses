<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class PaginationRequest
{
    #[Assert\NotBlank(message: "Page number is required.")]
    #[Assert\Positive(message: "Page number must be greater than 0.")]
    public ?int $page;

    #[Assert\NotBlank(message: "Limit is required.")]
    #[Assert\Positive(message: "Limit must be greater than 0.")]
    #[Assert\LessThan(101, message: "Limit must not exceed 100.")]
    public ?int $limit;

    public function __construct(?int $page = 1, ?int $limit = 10)
    {
        $this->page = $page;
        $this->limit = $limit;
    }
}