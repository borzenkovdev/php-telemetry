<?php

use PHPUnit\Framework\TestCase;
use Telemetry\Drivers\FileDriver;

class FileDriverTest extends TestCase
{
    private string $testFilePath;

    protected function setUp(): void
    {
        // Path to a temporary file for testing
        $this->testFilePath = __DIR__ . '/test_log_file.log';
    }

    protected function tearDown(): void
    {
        // Remove the test file if it exists after each test
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
    }

    public function testCreatesFileIfNotExists()
    {
        $this->assertFalse(file_exists($this->testFilePath));
        $fileDriver = new FileDriver($this->testFilePath);
        $this->assertTrue(file_exists($this->testFilePath));
    }

    public function testThrowsExceptionIfFileIsNotWritable()
    {
        // Create a file and set it to read-only
        file_put_contents($this->testFilePath, '');
        chmod($this->testFilePath, 0444); // Set file to read-only

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Log file is not writable');

        // Attempt to create the driver with a read-only file
        new FileDriver($this->testFilePath);
    }
}
