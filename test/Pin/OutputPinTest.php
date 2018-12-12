<?php

namespace PiPHP\Test\GPIO\Pin;

use PHPUnit\Framework\TestCase;
use PiPHP\GPIO\FileSystem\FileSystemInterface;
use PiPHP\GPIO\GPIO;
use PiPHP\Test\GPIO\FileSystem\VFS;

class OutputPinTest extends TestCase
{
    public function testOutputPin()
    {
        $vfs = new VFS();
        $gpio = new GPIO($vfs);

        $pin = $gpio->getOutputPin(2);

        $this->assertEquals('2', $vfs->getContents('/sys/class/gpio/export'));
        $this->assertEquals('out', $vfs->getContents('/sys/class/gpio/gpio2/direction'));

        $pin->setValue(1);

        $this->assertEquals(1, $vfs->getContents('/sys/class/gpio/gpio2/value'));
        $this->assertEquals(1, $pin->getValue());
    }

    public function testOutputPinIsNotDoubleExported()
    {
        // Prepare a filesystem where pin 2 has already been exported
        $fs = $this->getMockBuilder(FileSystemInterface::class)->getMock();
        $fs->expects($this->exactly(2))
            ->method('exists')
            ->withConsecutive(['/sys/class/gpio/gpio2'], ['/sys/class/gpio/gpio2/direction'])
            ->willReturn(true);

        $fs->expects($this->once())
            ->method('isDir')
            ->with('/sys/class/gpio/gpio2')
            ->willReturn(true);

        $fs->expects($this->once())
            ->method('getContents')
            ->with('/sys/class/gpio/gpio2/direction')
            ->willReturn('out');

        // Make sure the pin will not be exported again
        $fs->expects($this->never())
            ->method('putContents')
            ->with('/sys/class/gpio/gpio2/export', $this->anything());

        $gpio = new GPIO($fs);
        $gpio->getOutputPin(2);
    }
}
