<?php

declare(strict_types=1);

namespace app\Provider\Gateway;

use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

abstract class AbstractProvider
{
    private HttpClientInterface $client;
    private LoggerInterface $logger;

    public function __construct(HttpClientInterface $client, LoggerInterface $logger)
    {
        $this->client = $client;
        $this->logger = $logger;
    }

    abstract public function getApiUrl(): string;

    public function request(array $query = []): ?array
    {
        try {
            $response = $this->client->request(
                'GET',
                $this->getApiUrl() . '/' . array_shift($query),
            );

            if ($response->getStatusCode() === 200 && $response->getHeaders()) {
                return $response->toArray();
            }
        } catch (ExceptionInterface $exception) {
            $this->logger->error(
                sprintf(
                    'Message: %s ' . PHP_EOL . 'StackTrace: %s',
                    $exception->getMessage(),
                    $exception->getTraceAsString(),
                )
            );
        }

        return null;
    }
}
