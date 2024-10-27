<?php

namespace Telemetry;

use DateTime;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel as PsrLogLevel;
use Ramsey\Uuid\Uuid;
use Stringable;

class Telemetry extends AbstractLogger
{
    private DriverInterface $driver;
    private ?string $transactionId = null;
    private ?float $transactionStartTime = null;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param $level
     * @param string|Stringable $message
     * @param array $context
     * @return void
     */
    public function log($level, string|Stringable $message, array $context = []): void
    {
        $formattedMessage = $this->formatMessage($level, $message, $context);
        $this->driver->write($formattedMessage);
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @return string
     */
    private function formatMessage(string $level, string $message, array $context = []): string
    {
        $timestamp = (new DateTime())->format('Y/m/d');

        // If transactions was set - add transactionId to log
        if ($this->transactionId !== null) {
            $timestamp .= "/[{$this->transactionId}]";
        }

        $contextString = '';

        foreach ($context as $key => $value) {
            $contextString .= " $key=$value";
        }

        return "[$timestamp] [$level] $message $contextString";
    }

    /**
     * @return void
     */
    public function beginTransaction(): void
    {
        $this->transactionId = Uuid::uuid4()->toString();
        $this->transactionStartTime = microtime(true);

        $this->log(PsrLogLevel::INFO, 'Transaction started');
    }

    /**
     * @return void
     */
    public function endTransaction(): void
    {
        if ($this->transactionId === null || $this->transactionStartTime === null) {
            $this->log(PsrLogLevel::WARNING, 'No active transaction to end');
        }

        $duration = round(microtime(true) - $this->transactionStartTime, 2);

        $this->log(PsrLogLevel::INFO, 'Transaction ended', [
            "Duration" => "{$duration} seconds"
        ]);

        // Reset transaction
        $this->transactionId = null;
        $this->transactionStartTime = null;
    }
}
