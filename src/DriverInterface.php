<?php

namespace Telemetry;

interface DriverInterface
{
    public function write(string $message): void;
}
