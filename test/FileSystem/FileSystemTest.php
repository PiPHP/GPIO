<?php

namespace PiPHP\Test\GPIO\FileSystem;

use PiPHP\GPIO\FileSystem\FileSystem;
use RuntimeException;

class FileSystemTest extends \PHPUnit_Framework_TestCase
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
        unlink($dummyPath);
    }

    public function testBadFile()
    {
        $this->expectException(RuntimeException::class);
        (new FileSystem())->getContents(__DIR__ . '/this/file/path/does/not/exist');
    }
}
