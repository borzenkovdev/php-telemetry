<?php

namespace Telemetry\Drivers;

use Exception;
use Telemetry\DriverInterface;

class FileDriver implements DriverInterface
{
    private string $filePath;

    /**
     * @param string $filePath
     * @throws Exception
     */
    public function __construct(string $filePath)
    {
        // Check if file exists or try to create it
        if (!file_exists($filePath)) {
            if (!touch($filePath)) {
                throw new Exception("Failed to create log file at: {$filePath}");
            }
        }

        // Check if the file is writable
        if (!is_writable($filePath)) {
            throw new Exception("Log file is not writable: {$filePath}");
        }

        $this->filePath = $filePath;
    }

    public function write(string $message): void
    {
        file_put_contents($this->filePath, $message . PHP_EOL, FILE_APPEND);
    }
}
