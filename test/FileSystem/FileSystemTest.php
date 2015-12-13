<?php

namespace PiPHP\Test\GPIO\FileSystem;

use PiPHP\GPIO\FileSystem\FileSystem;
use PiPHP\GPIO\FileSystem\StreamInterface;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    public function testFileSystem()
    {
        $fileSystem = new FileSystem();

        $stream = $fileSystem->open(__FILE__, 'r');
        $this->assertInstanceOf(StreamInterface::class, $stream);

        $declaration = $stream->read(5);
        $this->assertEquals('<?php', $declaration);

        $stream->close();
    }

    /**
     * @expectedException RuntimeException
     */
    public function testBadFile()
    {
        (new FileSystem())->open(__DIR__ . '/this/file/path/does/not/exist', 'r');
    }
}
