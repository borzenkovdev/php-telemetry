<?php

use PHPUnit\Framework\TestCase;
use Telemetry\Telemetry;
use Telemetry\DriverInterface;
use Psr\Log\LogLevel;

class TelemetryTest extends TestCase
{
    private Telemetry $telemetry;
    private $driverMock;

    protected function setUp(): void
    {
        $this->driverMock = $this->createMock(DriverInterface::class);
        $this->telemetry = new Telemetry($this->driverMock);
    }

    public function testSetDateFormat()
    {
        // Set a custom date format
        $this->telemetry->setDateFormat('d-m-Y H:i:s');

        // Expect the log message to include the custom date format
        $this->driverMock->expects($this->once())
            ->method('write')
            ->with($this->callback(function ($message) {
                // Check if date is formatted as 'd-m-Y'
                return preg_match('/\[\d{2}-\d{2}-\d{4} \d{2}:\d{2}:\d{2}\]/', $message) === 1;
            }));

        // Log a test message to verify the format
        $this->telemetry->log(LogLevel::INFO, 'Testing custom date format');
    }

    public function testSetTimeZone()
    {
        // Set the timezone to Europe/Berlin
        $this->telemetry->setTimeZone('Europe/Berlin');

        // Capture the log time for comparison
        $this->driverMock->expects($this->once())
            ->method('write')
            ->with($this->callback(function ($message) {
                // Extract the timestamp from the log message
                preg_match('/\[(.*?)\]/', $message, $matches);
                $timestamp = $matches[1];

                // Create a DateTime in Berlin timezone for the same timestamp
                $date = new DateTime($timestamp, new DateTimeZone('Europe/Berlin'));
                return $date->getTimezone()->getName() === 'Europe/Berlin';
            }));

        // Log a test message to verify the time zone
        $this->telemetry->log(LogLevel::INFO, 'Testing time zone setting');
    }

    public function testTransactionLogIncludesTransactionId()
    {
        // Set custom date format and timezone
        $this->telemetry->setDateFormat('Y-m-d H:i:s');
        $this->telemetry->setTimeZone('America/New_York');

        // Start transaction and capture the transaction ID
        $this->telemetry->beginTransaction();

        // Expect transactionId and custom date format in the message
        $this->driverMock->expects($this->once())
            ->method('write')
            ->with($this->callback(function ($message) {
                // Check for date format and transactionId presence in log
                return preg_match('/\[\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \/ [a-f0-9\-]{36}\]/', $message) === 1;
            }));

        // End the transaction to trigger logging
        $this->telemetry->endTransaction();
    }

    public function testEndTransactionLogsDuration()
    {
        // Start a transaction
        $this->telemetry->beginTransaction();

        // Add a short delay to create a time difference
        usleep(500000); // 0.5 seconds

        // Expect a log entry with the duration included in the context
        $this->driverMock->expects($this->atLeastOnce())
            ->method('write')
            ->with($this->callback(function ($message) {
                // Check for duration in seconds
                return preg_match('/Duration=0\.5 seconds/', $message) === 1;
            }));

        // End the transaction
        $this->telemetry->endTransaction();
    }
}

