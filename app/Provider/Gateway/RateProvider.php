<?php

declare(strict_types=1);

namespace app\Provider\Gateway;

use app\Provider\DTO\RateDTO;
use app\Provider\DTO\RateListDTO;
use App\Provider\Exception\ProviderResponseException;

class RateProvider extends AbstractProvider
{
    private const URL = 'https://api.monobank.ua/bank/currency';

    private const UAH = 'UAH';
    private const USD = 'USD';
    private const EUR = 'EUR';
    private const JPY = 'JPY';
    private const GBP = 'GBP';

    private const SUPPORTED_CURRENCIES = [
        840 => self::USD,
        978 => self::EUR,
        980 => self::UAH,
        392 => self::JPY,
        826 => self::GBP,
    ];

    protected function getApiUrl(): string
    {
        return self::URL;
    }

    /**
     * @throws ProviderResponseException
     */
    public function getData(): RateListDTO
    {
        $responseData = $this->request();
        $listDTO = new RateListDTO();

        if (!is_null($responseData)) {
            foreach ($responseData as $currency) {
                if (isset(self::SUPPORTED_CURRENCIES[$currency['currencyCodeA']])) {
                    $currencyName = self::SUPPORTED_CURRENCIES[$currency['currencyCodeA']];
                    $rate = $currency['rateCross'] ?? $currency['rateSell'] ?? 0;
                    $listDTO->add(new RateDTO($rate), $currencyName);
                }
            }

            return $listDTO;
        }

        throw ProviderResponseException::exception(static::class);
    }
}
