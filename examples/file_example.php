<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Telemetry\Telemetry;
use Telemetry\Drivers\FileDriver;
use Psr\Log\LogLevel;

$fileDriver = new FileDriver(__DIR__ . '/logs.txt');
$telemetry = new Telemetry($fileDriver);

$telemetry->log(LogLevel::INFO, 'File driver: Service started', ['origin' => 'file', 'customerId' => '123']);

$telemetry->beginTransaction();
$telemetry->log(LogLevel::DEBUG, 'File driver: Processing order', ['step' => '1']);
$telemetry->endTransaction();
