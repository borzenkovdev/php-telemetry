<?php

use PHPUnit\Framework\TestCase;
use Telemetry\Drivers\JsonFileDriver;

class JsonFileDriverTest extends TestCase
{
    private string $testFilePath;

    protected function setUp(): void
    {
        $this->testFilePath = sys_get_temp_dir() . '/test_log_file.log';
    }

    protected function tearDown(): void
    {
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    public function testCreatesFileIfNotExists()
    {
        $this->assertFalse(file_exists($this->testFilePath));
        $jsonDriver = new JsonFileDriver($this->testFilePath);
        $this->assertTrue(file_exists($this->testFilePath));
    }
}

