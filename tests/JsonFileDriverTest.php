<?php

use PHPUnit\Framework\TestCase;
use Telemetry\Drivers\JsonFileDriver;

class JsonFileDriverTest extends TestCase
{
    private string $testFilePath;

    protected function setUp(): void
    {
        $this->testFilePath = __DIR__ . '/test_json_log_file.json';
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

    public function testThrowsExceptionIfFileIsNotWritable()
    {
        file_put_contents($this->testFilePath, '');
        chmod($this->testFilePath, 0444);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Log file is not writable");

        new JsonFileDriver($this->testFilePath);
    }
}

