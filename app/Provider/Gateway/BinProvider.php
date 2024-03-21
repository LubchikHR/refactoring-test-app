<?php

declare(strict_types=1);

namespace app\Provider\Gateway;

use app\Provider\DTO\BinDTO;
use app\Provider\Exception\ProviderResponseException;

class BinProvider extends AbstractProvider
{
    private const URL = 'https://lookup.binlist.net';
    private int $bin;

    protected function getApiUrl(): string
    {
        return self::URL . '/' . $this->bin;
    }

    /**
     * @param int $bin
     *
     * @return BinDTO
     * @throws ProviderResponseException
     */
    public function getData(int $bin): BinDTO
    {
        $this->bin = $bin;
        $responseData = $this->request();

        if (is_null($responseData)
            || !isset($responseData['country']['alpha2'])
            || is_null($responseData['country']['alpha2'])
        ) {
            throw ProviderResponseException::exception(static::class);
        }

        return new BinDTO(
            $responseData['country']['alpha2'],
        );
    }
}
