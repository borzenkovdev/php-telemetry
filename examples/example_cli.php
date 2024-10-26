<?php
// example.php

require_once 'Telemetry/Telemetry.php';
require_once 'Telemetry/Drivers/CLIDriver.php';

use Psr\Log\LogLevel;
use Telemetry\Telemetry;
use Telemetry\Drivers\CLIDriver;

$telemetry = new Telemetry(new CLIDriver());
$telemetry->log(LogLevel::INFO, "Service started", ["origin" => "http", "customerId" => "123"]);

$telemetry->startTransaction("12345", ["operation" => "create_order"]);
$telemetry->log(LogLevel::DEBUG, "Processing order", ["step" => "1"]);
$telemetry->log(LogLevel::WARNING, "Slow response from DB", ["db" => "orders"]);
$telemetry->endTransaction("12345");
