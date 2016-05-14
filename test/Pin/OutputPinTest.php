<?php

namespace PiPHP\Test\GPIO\Pin;

use PiPHP\GPIO\GPIO;
use PiPHP\Test\GPIO\FileSystem\VFS;

class OutputPinTest extends \PHPUnit_Framework_TestCase
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
}
