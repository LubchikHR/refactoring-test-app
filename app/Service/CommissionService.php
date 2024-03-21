<?php

declare(strict_types=1);

namespace app\Service;

class CommissionService
{
    private const EUR = 'EUR';
    private const FIXED_PERCENT_FOR_EU_COUNTRY = 0.01;
    private const FIXED_PERCENT_FOR_OTHER = 0.02;
    private const EU_COUNTRY_ABBREVIATION = [
        'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR', 'HR',
        'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO', 'SE', 'SI', 'SK',
    ];

    public function calculate(string $country, string $currency, float $rate, float $amount): float
    {
        $percentByCountry = $this->isEuropeCountry($country)
            ? self::FIXED_PERCENT_FOR_EU_COUNTRY
            : self::FIXED_PERCENT_FOR_OTHER;

        if (self::EUR === $currency || $rate === 0) {
            return $this->roundUp($amount * $percentByCountry);
        }

        return $this->roundUp(($amount / $rate) * $percentByCountry);
    }

    private function isEuropeCountry(string $abbreviation): bool
    {
        return in_array($abbreviation, self::EU_COUNTRY_ABBREVIATION, true);
    }

    private function roundUp(float $number): float
    {
        return ceil($number * 100) / 100;
    }
}
