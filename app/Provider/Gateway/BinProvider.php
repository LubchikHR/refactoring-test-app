<?php

declare(strict_types=1);

namespace app\Provider\Gateway;

use app\Provider\DTO\BinDTO;
use app\Provider\Exception\ProviderResponseException;
use app\Provider\ProviderInterface;

class BinProvider extends AbstractProvider implements ProviderInterface
{
    private const URL = 'https://lookup.binlist.net';

    public function getApiUrl(): string
    {
        return self::URL;
    }

    /**
     * @param array $params
     *
     * @return BinDTO
     * @throws ProviderResponseException
     */
    public function getData(array $params = []): BinDTO
    {
        $responseData = $this->request($params);

        if (is_null($responseData)) {
            throw ProviderResponseException::exception(static::class);
        }

        return new BinDTO(
            $responseData['country']['alpha2'],
        );
    }
}
