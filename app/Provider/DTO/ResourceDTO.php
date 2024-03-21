<?php

declare(strict_types=1);

namespace app\Provider\DTO;

class ResourceDTO
{
    private int $bin;
    private float $amount;
    private string $currency;

    public function __construct(int $bin, float $amount, string $currency)
    {
        $this->bin = $bin;
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getBin(): int
    {
        return $this->bin;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
