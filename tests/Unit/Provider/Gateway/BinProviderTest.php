<?php

declare(strict_types=1);

namespace tests\Provider\Gateway;

use app\Provider\DTO\BinDTO;
use app\Provider\Exception\ProviderResponseException;
use app\Provider\Gateway\BinProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class BinProviderTest extends TestCase
{
    private BinProvider $provider;
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->provider = new BinProvider($this->httpClient, $this->logger);
    }

    public function testGetDataReturnsBinDTO(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $responseMock->expects($this->once())
            ->method('getHeaders')
            ->willReturn([true]);

        $responseMock->expects($this->once())
            ->method('toArray')
            ->willReturn([
                'country' => ['alpha2' => 'US'],
            ]);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);

        $binDTO = $this->provider->getData(['123456']);
        $this->assertInstanceOf(BinDTO::class, $binDTO);
        $this->assertSame('US', $binDTO->getCountryAbbreviation());
    }

    public function testGetDataThrowsProviderResponseException(): void
    {
        $responseMock = $this->createMock(ResponseInterface::class);
        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);

        $this->expectException(ProviderResponseException::class);
        $this->provider->getData(['123456']);
    }
}
