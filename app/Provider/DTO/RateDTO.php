<?php

declare(strict_types=1);

namespace app\Provider\DTO;

class RateDTO
{
    private float $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function getValue(): float
    {
        return $this->value;
    }
}
