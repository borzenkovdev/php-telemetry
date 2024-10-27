<?php

namespace Telemetry\Drivers;

use Exception;
use Telemetry\DriverInterface;

class JsonFileDriver implements DriverInterface
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

    /**
     * @param string $message
     * @return void
     */
    public function write(string $message): void
    {
        // Convert the message string to JSON format
        $logData = [
            'timestamp' => (new \DateTime())->format('Y-m-d H:i:s'),
            'message' => $message
        ];

        // Append the JSON-encoded log message to the file, each log on a new line
        file_put_contents($this->filePath, json_encode($logData) . PHP_EOL, FILE_APPEND);
    }
}
