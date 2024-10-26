<?php

namespace Telemetry;

use DateTime;
use DateTimeInterface;
use Psr\Log\AbstractLogger;
use Psr\Log\LogLevel;

class Telemetry extends AbstractLogger
{
    private DriverInterface $driver;

    /**
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $attributes
     * @return string
     */
    private function formatMessage(string $level, string $message, array $attributes = []): string
    {
        $timestamp = (new DateTime())->format(DateTimeInterface::ATOM);
        $attrString = '';

        foreach ($attributes as $key => $value) {
            $attrString .= " $key=$value";
        }
        return "[$timestamp] [$level] $message $attrString";
    }

    /**
     * @param string $level
     * @param string $message
     * @param array $attributes
     * @return void
     */
    public function log(string $level, string $message, array $attributes = []): void
    {
        $formattedMessage = $this->formatMessage($level, $message, $attributes);
        $this->driver->write($formattedMessage);
    }

    /**
     * @param string $id
     * @param array $attributes
     * @return void
     */
    public function startTransaction(string $id, array $attributes = []): void
    {
        $formattedMessage = $this->formatMessage('INFO', 'Transaction started', array_merge($attributes, ["TransactionID" => $id]));
        $this->driver->write($formattedMessage);
    }

    /**
     * @param string $id
     * @return void
     */
    public function endTransaction(string $id): void
    {
        $formattedMessage = $this->formatMessage('INFO', 'Transaction ended', ["TransactionID" => $id]);
        $this->driver->write($formattedMessage);
    }
}

