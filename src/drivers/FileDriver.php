<?php

namespace Telemetry\Drivers;

use Telemetry\DriverInterface;

class FileDriver implements DriverInterface
{
    private string $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    public function write(string $message): void
    {
        file_put_contents($this->filePath, $message . PHP_EOL, FILE_APPEND);
    }
}
