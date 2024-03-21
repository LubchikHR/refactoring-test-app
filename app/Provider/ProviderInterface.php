<?php

declare(strict_types=1);

namespace app\Provider;

interface ProviderInterface
{
    public function getData(array $params = []);
}
