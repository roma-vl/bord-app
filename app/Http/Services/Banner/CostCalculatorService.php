<?php

namespace App\Http\Services\Banner;

class CostCalculatorService
{
    private $price;

    public function __construct(int $price)
    {
        $this->price = $price;
    }

    public function calc(int $views): int
    {
        return floor($this->price * ($views / 1000));
    }
}
