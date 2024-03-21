<?php

declare(strict_types=1);

namespace app\Provider\Exception;

class ProviderResponseException extends \Exception
{
    public static function exception(string $bankClass): self
    {
        return new static(sprintf('Something happened with provider: %s', $bankClass));
    }
}
