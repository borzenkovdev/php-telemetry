# Telemetry - This package offer a variety of features for meaningful logging.

A flexible and configurable logging library for PHP, supporting transactions with unique IDs, customizable date formats, and time zones.
This library simplifies tracking logs with transaction IDs and performance timings and  implements the
[PSR-3 specification](https://www.php-fig.org/psr/psr-3/).

## Features
* Supports logging messages with various log levels.
* Provides unique transaction IDs for tracking grouped logs.
* Customizable date and time formats.
* Allows setting a custom time zone.
* Supports PSR-3 log levels for compatibility.

## Installation

Install the latest version with:

```bash
$ composer require borzenkovdev/php-telemetry
```

## Usage

### Basic Usage

```php
<?php

use Telemetry\Telemetry;
use Telemetry\drivers\CliDriver;
use Psr\Log\LogLevel;

$telemetry = new Telemetry(new CliDriver());

$telemetry->log(LogLevel::INFO, 'Service started', ['origin' => 'http', 'customerId' => '123']);

```

### Basic Usage with transaction

```php
<?php

use Telemetry\Telemetry;
use Telemetry\drivers\CliDriver;
use Psr\Log\LogLevel;

$telemetry = new Telemetry(new CliDriver());

$telemetry->beginTransaction();
$telemetry->log(LogLevel::DEBUG, 'Processing order', ['step' => '1']);
$telemetry->log(LogLevel::WARNING, 'Slow response from DB', ['db' => 'orders']);
$telemetry->endTransaction();
```

For more examples, see the [examples](https://github.com/borzenkovdev/php-telemetry/tree/main/examples) folder.

## Configuring Date Format and Time Zone

You can set a custom date format and time zone using ```setDateFormat``` and ```setTimeZone```.

```php
$telemetry->setDateFormat('Y-m-d H:i:s');
$telemetry->setTimeZone('America/New_York');
```
This configuration will format timestamps according to the specified format and time zone.

##
