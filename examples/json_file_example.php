<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Telemetry\Telemetry;
use Telemetry\Drivers\JsonFileDriver;
use Psr\Log\LogLevel;

$jsonFileDriver = new JsonFileDriver(__DIR__ . '/logs.json');
$telemetry = new Telemetry($jsonFileDriver);

$telemetry->log(LogLevel::INFO, 'JSON File driver: Service started', ['origin' => 'json', 'customerId' => '123']);

$telemetry->beginTransaction();
$telemetry->log(LogLevel::DEBUG, 'JSON File driver: Processing order', ['step' => '1']);
$telemetry->endTransaction();
