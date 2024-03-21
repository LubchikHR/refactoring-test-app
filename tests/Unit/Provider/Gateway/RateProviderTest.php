<?php

declare(strict_types=1);

namespace tests\Provider\Gateway;

use app\Provider\DTO\RateDTO;
use app\Provider\DTO\RateListDTO;
use app\Provider\Exception\ProviderResponseException;
use app\Provider\Gateway\RateProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class RateProviderTest extends TestCase
{
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private RateProvider $rateProvider;

    protected function setUp(): void
    {
        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->rateProvider = new RateProvider($this->httpClient, $this->logger);
    }

    public function testGetDataReturnsRateListDTO(): void
    {
        $responseData = [
            ['currencyCodeA' => 840, 'rateCross' => 27.5],
            ['currencyCodeA' => 978, 'rateCross' => 32.1],
        ];

        $expectedRateListDTO = new RateListDTO();
        $expectedRateListDTO->add(new RateDTO(27.5), 'USD');
        $expectedRateListDTO->add(new RateDTO(32.1), 'EUR');

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->expects($this->once())
            ->method('toArray')
            ->willReturn($responseData);
        $responseMock->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(200);

        $responseMock->expects($this->once())
            ->method('getHeaders')
            ->willReturn([true]);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($responseMock);

        $actualRateListDTO = $this->rateProvider->getData();

        $this->assertEquals($expectedRateListDTO, $actualRateListDTO);
    }

    public function testGetDataThrowsProviderResponseException(): void
    {
        $response = $this->createMock(ResponseInterface::class);
        $this->expectException(ProviderResponseException::class);

        $this->httpClient->expects($this->once())
            ->method('request')
            ->willReturn($response);

        $this->rateProvider->getData();
    }
}
