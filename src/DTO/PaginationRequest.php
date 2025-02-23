<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class PaginationRequest
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public int $page;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\LessThan(101)] // Максимальне значення 100
    public int $limit;

    public function __construct(int $page = 1, int $limit = 10)
    {
        $this->page = $page;
        $this->limit = $limit;
    }
}