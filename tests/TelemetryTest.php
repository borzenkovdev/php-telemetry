<?php

use PHPUnit\Framework\TestCase;
use Telemetry\Telemetry;
use Psr\Log\LogLevel;
use Telemetry\DriverInterface;

class TelemetryTest extends TestCase
{
    private Telemetry $telemetry;
    private $driverMock;

    /**
     * @throws \PHPUnit\Framework\MockObject\Exception
     */
    protected function setUp(): void
    {
        // Create a mock driver
        $this->driverMock = $this->createMock(DriverInterface::class);
        $this->telemetry = new Telemetry($this->driverMock);
    }

    public function testBeginTransactionGeneratesTransactionIdAndLogsStart()
    {
        // Expect that the write method is called with a start transaction message containing TransactionID
        $this->driverMock->expects($this->once())
            ->method('write')
            ->with($this->stringContains('Transaction started')
        );

        $this->telemetry->beginTransaction();

        // Verify that the transactionId is set
        $reflection = new \ReflectionClass($this->telemetry);
        $transactionIdProperty = $reflection->getProperty('transactionId');
        $transactionIdProperty->setAccessible(true);
        $this->assertNotNull($transactionIdProperty->getValue($this->telemetry));
    }

    public function testLogMethodIncludesTransactionIdWhenInsideTransaction()
    {
        // Begin a transaction
        $this->telemetry->beginTransaction();

        // Expect that the write method includes transactionId in the date format
        $this->driverMock->expects($this->once())
            ->method('write')
            ->with($this->stringContains('/[TransactionID]'));

        $this->telemetry->log(LogLevel::INFO, "Test message inside transaction");
    }

    public function testEndTransactionLogsEndWithDuration()
    {
        // Start the transaction and set the start time
        $this->telemetry->beginTransaction();

        // Add a short delay to create a time difference
        usleep(500000); // 0.5 seconds

        // Verify that the write method logs transaction end with duration
        $this->driverMock->expects($this->atLeastOnce())
            ->method('write')
            ->with($this->stringContains('Transaction ended')
                ->and($this->stringContains('Duration=0.5'))
            );

        // End the transaction
        $this->telemetry->endTransaction();

        // Check that the transactionId is cleared after the transaction ends
        $reflection = new \ReflectionClass($this->telemetry);
        $transactionIdProperty = $reflection->getProperty('transactionId');
        $transactionIdProperty->setAccessible(true);
        $this->assertNull($transactionIdProperty->getValue($this->telemetry));
    }
}

