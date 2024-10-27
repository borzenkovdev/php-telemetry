<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Telemetry\Telemetry;
use Telemetry\Drivers\CliDriver;
use Psr\Log\LogLevel;

$cliDriver = new CliDriver();
$telemetry = new Telemetry($cliDriver);

// Set custom date format and time zone
$telemetry->setDateFormat('Y-m-d H:i:s');
$telemetry->setTimeZone('Europe/Berlin');

// Logging without transaction
$telemetry->log(LogLevel::INFO, 'Service started', ['origin' => 'http', 'customerId' => '123']);

// Start transaction
$telemetry->beginTransaction();
usleep(500000);
$telemetry->log(LogLevel::DEBUG, 'Processing order', ['step' => '1']);
usleep(300000);
$telemetry->log(LogLevel::WARNING, 'Slow response from DB', ['db' => 'orders']);

// End transaction
$telemetry->endTransaction();
