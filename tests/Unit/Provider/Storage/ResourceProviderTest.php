<?php

declare(strict_types=1);

namespace tests\Provider\Storage;

use app\Provider\DTO\ResourceDTO;
use app\Provider\Storage\ResourceProvider;
use PHPUnit\Framework\TestCase;

class ResourceProviderTest extends TestCase
{
    public function testGetData(): void
    {
        $data = [
            '{"bin":123456,"amount":100.5,"currency":"USD"}',
            '{"bin":789012,"amount":200.75,"currency":"EUR"}',
        ];

        $tempFile = tempnam(sys_get_temp_dir(), 'resource_test');
        file_put_contents($tempFile, implode(PHP_EOL, $data));

        $provider = new ResourceProvider($tempFile);
        $iterator = $provider->getData();

        $expectedResults = [
            new ResourceDTO(123456, 100.5, 'USD'),
            new ResourceDTO(789012, 200.75, 'EUR'),
        ];

        $actualResults = [];
        foreach ($iterator as $item) {
            $actualResults[] = $item;
        }

        $this->assertEquals($expectedResults, $actualResults);

        unlink($tempFile);
    }
}
