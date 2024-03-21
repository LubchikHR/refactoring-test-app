<?php

declare(strict_types=1);

namespace tests\Functional;

use app\Main;
use app\Provider\DTO\RateDTO;
use app\Provider\DTO\RateListDTO;
use app\Provider\DTO\ResourceDTO;
use app\Provider\Gateway\BinProvider;
use app\Provider\Gateway\RateProvider;
use app\Provider\Storage\ResourceProvider;
use app\Service\CommissionService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use app\Provider\DTO\BinDTO;

class MainTest extends TestCase
{
    public function testRun(): void
    {
        $rateProvider = $this->createMock(RateProvider::class);
        $binProvider = $this->createMock(BinProvider::class);
        $commissionService = $this->createMock(CommissionService::class);
        $resourceProvider = $this->createMock(ResourceProvider::class);
        $logger = $this->createMock(LoggerInterface::class);

        $rateDTO = new RateDTO(1.23);
        $resourceDTO = new ResourceDTO(123456, 100.0, 'USD');
        $rateListDTO = new RateListDTO();
        $rateListDTO->add($rateDTO, 'USD');

        $rateProvider->expects($this->once())
            ->method('getData')
            ->willReturn($rateListDTO);

        $binProvider->expects($this->once())
            ->method('getData')
            ->with($resourceDTO->getBin())
            ->willReturn(new BinDTO('US'));

        $commissionService->expects($this->once())
            ->method('calculate')
            ->with('US', 'USD', 1.23, 100.0)
            ->willReturn(1.5);

        $resourceProvider->expects($this->once())
            ->method('getData')
            ->willReturn([$resourceDTO]);

        $main = new Main($rateProvider, $binProvider, $commissionService, $resourceProvider, $logger);

        ob_start();
        $main->run();
        $output = ob_get_clean();

        $this->assertEquals('1.5', $output);
    }
}
