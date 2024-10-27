<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Telemetry\Telemetry;
use Telemetry\Drivers\CliDriver;
use Psr\Log\LogLevel;

$cliDriver = new CliDriver();
$telemetry = new Telemetry($cliDriver);

$telemetry->log(LogLevel::INFO, 'CLI driver: Service started', ['origin' => 'cli', 'customerId' => '123']);

$telemetry->beginTransaction();
$telemetry->log(LogLevel::DEBUG, 'CLI driver: Processing order', ['step' => '1']);
$telemetry->endTransaction();
