<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Telemetry\Telemetry;
use Telemetry\Drivers\CliDriver;
use Psr\Log\LogLevel;

$telemetry = new Telemetry(new CliDriver());

// Logging without transaction
$telemetry->log(LogLevel::INFO, "Service started", ["origin" => "http", "customerId" => "123"]);

// Start transaction
$telemetry->beginTransaction();
usleep(500000);
$telemetry->log(LogLevel::DEBUG, "Processing order", ["step" => "1"]);
usleep(300000);
$telemetry->log(LogLevel::WARNING, "Slow response from DB", ["db" => "orders"]);

// End transaction
$telemetry->endTransaction();
