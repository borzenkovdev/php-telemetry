<?php

use PHPUnit\Framework\TestCase;
use Telemetry\Drivers\FileDriver;

class FileDriverTest extends TestCase
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
        $fileDriver = new FileDriver($this->testFilePath);
        $this->assertTrue(file_exists($this->testFilePath));
    }
}
