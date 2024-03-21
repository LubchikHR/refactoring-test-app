<?php

declare(strict_types=1);

namespace app\Provider\Storage;

use app\Provider\DTO\ResourceDTO;

class ResourceProvider
{
    private string $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function getData(): iterable
    {
        $result = [];

        if ($file = fopen($this->filePath, 'r')) {
            while (($line = fgets($file)) !== false) {
                $data = json_decode($line, true, 512, JSON_THROW_ON_ERROR);

                yield new ResourceDTO((int) $data['bin'], (float) $data['amount'], $data['currency']);
            }

            fclose($file);
        }

        return $result;
    }
}
