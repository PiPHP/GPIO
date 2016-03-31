<?php

namespace PiPHP\Test\GPIO\FileSystem;

use PiPHP\GPIO\FileSystem\FileSystem;

class FileSystemTest extends \PHPUnit_Framework_TestCase
{
    public function testFileSystem()
    {
        $fileSystem = new FileSystem();

        $this->assertEquals(file_get_contents(__FILE__), $fileSystem->getContents(__FILE__));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testBadFile()
    {
        (new FileSystem())->getContents(__DIR__ . '/this/file/path/does/not/exist');
    }
}
