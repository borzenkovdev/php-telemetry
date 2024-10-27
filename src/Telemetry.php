<?php

namespace Telemetry;

use DateTime;
use DateTimeZone;
use Exception;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel as PsrLogLevel;
use Ramsey\Uuid\Uuid;
use Stringable;

class Telemetry extends AbstractLogger
{
    private DriverInterface $driver;
    private ?string $transactionId = null;
    private ?float $transactionStartTime = null;

    /**
     * The default date/time format for log messages written to a file.
     * Feeds into the `$format` property.
     */
    private string $dateFormat = 'Y-m-d H:i:s';

    /**
     * Timezone for date/time values.
     */
    private DateTimeZone $timeZone;

    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
        $this->timeZone = new DateTimeZone('UTC'); // Default time zone
    }

    /**
     * @param $level
     * @param string|Stringable $message
     * @param array $context
     * @return void
     * @throws Exception
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
     * @throws Exception
     */
    private function formatMessage(string $level, string $message, array $context = []): string
    {
        $timeStamp = (new DateTime('now', $this->timeZone))->format($this->dateFormat);

        // If transactions was set - add transactionId to log
        if ($this->transactionId !== null) {
            $timeStamp .= " / $this->transactionId";
        }

        $contextString = '';

        foreach ($context as $key => $value) {
            $contextString .= " $key=$value";
        }

        return "[$timeStamp] [$level] $message $contextString";
    }

    /**
     * @return void
     * @throws Exception
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
            'Duration' => "$duration seconds"
        ]);

        // Reset transaction
        $this->transactionId = null;
        $this->transactionStartTime = null;
    }

    /**
     * @param string $format
     * @return void
     */
    public function setDateFormat(string $format): void
    {
        $this->dateFormat = $format;
    }

    /**
     * @throws Exception
     */
    public function setTimeZone(string $timeZone): void
    {
        $this->timeZone = new DateTimeZone($timeZone);
    }
}
