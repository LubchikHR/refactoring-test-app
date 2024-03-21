<?php

require_once 'autoload.php';

$logger = new \Monolog\Logger('app');
$httpClient = \Symfony\Component\HttpClient\HttpClient::create();
$main = new \app\Main(
    new \app\Provider\Gateway\RateProvider($httpClient, $logger),
    new \app\Provider\Gateway\BinProvider($httpClient, $logger),
    new \app\Service\CommissionService(),
    new \app\Provider\Storage\ResourceProvider(INPUT_RESOURCE),
    $logger,
);

$main->run();
