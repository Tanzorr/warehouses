<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
class PaginationRequest
{
    #[Assert\Positive(message: "Page number must be greater than 0.")]
    public mixed $page;

    #[Assert\Positive(message: "Limit must be greater than 0.")]
    #[Assert\LessThan(101, message: "Limit must not exceed 100.")]
    public mixed $limit;

    public function __construct($page = 1,  $limit = 10)
    {
        $this->page = $page;
        $this->limit = $limit;
    }
}