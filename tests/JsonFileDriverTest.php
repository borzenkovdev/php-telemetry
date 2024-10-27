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
        // Remove the test file if it exists after each test
        if (file_exists($this->testFilePath)) {
            // Reset permissions before deleting
            chmod($this->testFilePath, 0666);
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
        $this->expectExceptionMessage('Log file is not writable');

        new JsonFileDriver($this->testFilePath);
    }
}

