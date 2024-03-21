<?php

declare(strict_types=1);

namespace tests\Service;

use app\Service\CommissionService;
use PHPUnit\Framework\TestCase;

class CommissionServiceTest extends TestCase
{
    public function testCalculateWithZeroRate(): void
    {
        $service = new CommissionService();
        $amount = 100.0;
        $rate = 0.0;

        $result = $service->calculate('CZ', 'EUR', $rate, $amount);
        $this->assertEquals(1.0, $result);
    }

    public function testCalculateWithNonZeroRate(): void
    {
        $service = new CommissionService();
        $amount = 100.0;
        $rate = 1.5;

        $result = $service->calculate('US', 'EUR', $rate, $amount);
        $this->assertEquals(2.0, $result);
    }

    public function testCalculateWithNonEuropeanCountry(): void
    {
        $service = new CommissionService();
        $amount = 100.0;
        $rate = 1.5;

        $result = $service->calculate('US', 'USD', $rate, $amount);
        $this->assertEquals(1.34, $result);
    }

    public function testCalculateWithEuropeanCountry(): void
    {
        $service = new CommissionService();
        $amount = 100.0;
        $rate = 1.5;

        $result = $service->calculate('DE', 'EUR', $rate, $amount);
        $this->assertEquals(1.0, $result);
    }
}
