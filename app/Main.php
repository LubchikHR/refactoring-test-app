<?php

declare(strict_types=1);

namespace app;

use app\Provider\DTO\ResourceDTO;
use app\Provider\Exception\ProviderResponseException;
use app\Provider\Gateway\BinProvider;
use app\Provider\Gateway\RateProvider;
use app\Provider\Storage\ResourceProvider;
use app\Service\CommissionService;
use Psr\Log\LoggerInterface;

final class Main
{
    private RateProvider $rateProvider;
    private BinProvider $bitProvider;
    private CommissionService $commissionService;
    private ResourceProvider $resourceService;
    private LoggerInterface $logger;

    public function __construct(
        RateProvider $rateProvider,
        BinProvider $bitProvider,
        CommissionService $commissionService,
        ResourceProvider $resourceService,
        LoggerInterface $logger
    ) {
        $this->rateProvider = $rateProvider;
        $this->bitProvider = $bitProvider;
        $this->commissionService = $commissionService;
        $this->resourceService = $resourceService;
        $this->logger = $logger;
    }

    public function run(): void
    {
        try {
            $ratesDTOs = $this->rateProvider->getData();

            /** @var ResourceDTO $dto */
            foreach ($this->resourceService->getData() as $dto) {
                $binDTO = $this->bitProvider->getData([$dto->getBin()]);

                echo $this->commissionService->calculate(
                    $binDTO->getCountryAbbreviation(),
                    $dto->getCurrency(),
                    $ratesDTOs->getRate($dto->getCurrency())->getValue(),
                    $dto->getAmount(),
                    );
            }
        } catch (ProviderResponseException|\Exception $exception) {
            $this->logger->error(
                sprintf(
                    'Message: %s '.PHP_EOL.'StackTrace: %s',
                    $exception->getMessage(),
                    $exception->getTraceAsString(),
                )
            );
        }
    }
}
