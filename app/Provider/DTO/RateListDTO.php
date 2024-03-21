<?php

declare(strict_types=1);

namespace app\Provider\DTO;

class RateListDTO
{
    private array $ratesDTO = [];

    public function add(RateDTO $dto, string $currencyKey): void
    {
        $this->ratesDTO[$currencyKey] = $dto;
    }

    public function getRate(string $currency): RateDTO
    {
        if (!isset($this->ratesDTO[$currency])) {
            throw new \Exception(sprintf('This currency "%s" is not support', $currency));
        }

        return $this->ratesDTO[$currency];
    }
}
