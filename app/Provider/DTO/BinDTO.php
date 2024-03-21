<?php

declare(strict_types=1);

namespace app\Provider\DTO;

class BinDTO
{
    private string $country;

    public function __construct(string $country)
    {
        $this->country = $country;
    }

    public function getCountryAbbreviation(): string
    {
        return $this->country;
    }
}
