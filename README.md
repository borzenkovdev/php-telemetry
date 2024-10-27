# Telemetry - lightweight PHP Logging Library with Multi-Driver Support
![Packagist Version](https://img.shields.io/packagist/v/borzenkovdev/php-telemetry)
![Packagist PHP Version Support](https://img.shields.io/packagist/php-v/borzenkovdev/php-telemetry)
![Packagist Downloads](https://img.shields.io/packagist/dt/borzenkovdev/php-telemetry)

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

## Adding a Custom Driver
To add a custom driver, implement the DriverInterface and define the write method to handle log storage.

### Example
```php
<?php

namespace Telemetry\Drivers;

use Telemetry\DriverInterface;

class CustomDriver implements DriverInterface
{
    public function write(string $message): void
    {
        // Custom logic to store or display log message
    }
}
```
### Usage
```php
<?php

$customDriver = new CustomDriver();
$telemetry = new Telemetry($customDriver);
$telemetry->log(LogLevel::INFO, "Testing custom driver");

```
