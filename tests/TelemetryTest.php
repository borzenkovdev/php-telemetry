<?php

use PHPUnit\Framework\TestCase;
use Telemetry\Telemetry;
use Telemetry\LogLevel;
use Telemetry\DriverInterface;

class TelemetryTest extends TestCase
{
    private $telemetry;
    private $driverMock;

    protected function setUp(): void
    {
        // Создаем mock драйвера
        $this->driverMock = $this->createMock(DriverInterface::class);

        // Передаем mock драйвер в Telemetry
        $this->telemetry = new Telemetry($this->driverMock);
    }

    public function testLogMethodFormatsMessageCorrectly()
    {
        $message = "Test log message";
        $attributes = ["user" => "123"];

        // Ожидаем, что метод write драйвера будет вызван с определенным форматом строки
        $this->driverMock->expects($this->once())
            ->method('write')
            ->with($this->stringContains("INFO")
                ->and($this->stringContains("Test log message"))
                ->and($this->stringContains("user=123"))
            );

        $this->telemetry->log(LogLevel::INFO, $message, $attributes);
    }

    public function testStartTransactionLogsTransactionStart()
    {
        $transactionId = "12345";
        $attributes = ["operation" => "create_order"];

        // Ожидаем, что метод write будет вызван с сообщением о начале транзакции
        $this->driverMock->expects($this->once())
            ->method('write')
            ->with($this->stringContains("Transaction started")
                ->and($this->stringContains("TransactionID=12345"))
                ->and($this->stringContains("operation=create_order"))
            );

        $this->telemetry->startTransaction($transactionId, $attributes);
    }

    public function testEndTransactionLogsTransactionEnd()
    {
        $transactionId = "12345";

        // Ожидаем, что метод write будет вызван с сообщением о завершении транзакции
        $this->driverMock->expects($this->once())
            ->method('write')
            ->with($this->stringContains("Transaction ended")
                ->and($this->stringContains("TransactionID=12345"))
            );

        $this->telemetry->endTransaction($transactionId);
    }
}
