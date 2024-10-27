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
        if (!file_exists($filePath) && !touch($filePath)) {
            throw new Exception("Failed to create log file at: $filePath");
        }

        $fileHandle = fopen($filePath, 'a');

        if ($fileHandle === false) {
            throw new Exception("Log file is not writable: $filePath");
        }

        fclose($fileHandle);
        $this->filePath = $filePath;
    }

    public function write(string $message): void
    {
        file_put_contents($this->filePath, $message . PHP_EOL, FILE_APPEND);
    }
}
