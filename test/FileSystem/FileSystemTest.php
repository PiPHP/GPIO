<?php

namespace PiPHP\Test\GPIO\FileSystem;

use PHPUnit\Framework\TestCase;
use PiPHP\GPIO\FileSystem\FileSystem;
use RuntimeException;

class FileSystemTest extends TestCase
{
    public function testFileSystem()
    {
        $fileSystem = new FileSystem();
        $thisFile = file_get_contents(__FILE__);

        // Test open()
        $stream = $fileSystem->open(__FILE__, 'r');
        $this->assertEquals($thisFile, stream_get_contents($stream));
        fclose($stream);

        // Test getContents()
        $this->assertEquals($thisFile, $fileSystem->getContents(__FILE__));

        // Test putContents()
        $dummyPath = __DIR__ . '.dummy';
        $fileSystem->putContents($dummyPath, 'foo');
        $this->assertEquals('foo', $fileSystem->getContents($dummyPath));
        $this->assertTrue($fileSystem->exists($dummyPath));
        $this->assertFalse($fileSystem->isDir($dummyPath));
        unlink($dummyPath);
        $this->assertFalse($fileSystem->exists($dummyPath));
    }

    public function testBadFile()
    {
        $this->expectException(RuntimeException::class);
        (new FileSystem())->getContents(__DIR__ . '/this/file/path/does/not/exist');
    }
}
