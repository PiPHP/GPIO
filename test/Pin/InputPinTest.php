<?php

namespace PiPHP\Test\GPIO\Pin;

use PHPUnit\Framework\TestCase;
use PiPHP\GPIO\GPIO;
use PiPHP\Test\GPIO\FileSystem\VFS;

class InputPinTest extends TestCase
{
    public function testInputPin()
    {
        $vfs = new VFS();
        $gpio = new GPIO($vfs);

        $pin = $gpio->getInputPin(2);

        $this->assertEquals('2', $vfs->getContents('/sys/class/gpio/export'));
        $this->assertEquals('in', $vfs->getContents('/sys/class/gpio/gpio2/direction'));

        $pin->setEdge('both');

        $this->assertEquals('both', $pin->getEdge());
        $this->assertEquals('both', $vfs->getContents('/sys/class/gpio/gpio2/edge'));

        $vfs->putContents('/sys/class/gpio/gpio2/value', '1');

        $this->assertEquals(1, $pin->getValue());

        $pin->unexport();
        $this->assertEquals('2', $vfs->getContents('/sys/class/gpio/unexport'));
    }
}
