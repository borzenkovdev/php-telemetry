<?php

namespace Telemetry\Drivers;

use Telemetry\DriverInterface;

class CliDriver implements DriverInterface
{
    public function write(string $message): void
    {
        echo $message . PHP_EOL;
    }
}

