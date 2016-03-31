<?php

namespace PiPHP\Test\GPIO;

use PiPHP\GPIO\FileSystem\FileSystemInterface;
use PiPHP\GPIO\Pin;
use PiPHP\GPIO\PinFactory;

class PinTest extends \PHPUnit_Framework_TestCase
{
    const TEST_PIN_NUMBER = 5;

    public function testGetNumber()
    {
        $pin = $this->getPin();
        $this->assertEquals(self::TEST_PIN_NUMBER, $pin->getNumber());
    }

    public function testDirection()
    {
        $pin = $this->getPin('/sys/class/gpio/gpio' . self::TEST_PIN_NUMBER . '/direction');
        $pin->setDirection(Pin::DIRECTION_IN);
        $this->assertEquals(Pin::DIRECTION_IN, $pin->getDirection());
    }

    public function testValue()
    {
        $pin = $this->getPin('/sys/class/gpio/gpio' . self::TEST_PIN_NUMBER . '/value');
        $pin->setValue(Pin::VALUE_HIGH);
        $this->assertEquals(Pin::VALUE_HIGH, $pin->getValue());
    }

    public function testEdge()
    {
        $pin = $this->getPin('/sys/class/gpio/gpio' . self::TEST_PIN_NUMBER . '/edge');
        $pin->setEdge(Pin::EDGE_RISING);
        $this->assertEquals(Pin::EDGE_RISING, $pin->getEdge());
    }

    private function getPin($expectedFile = null)
    {
        $mockFileSystem = new FileSystemMock($expectedFile);
        return (new PinFactory($mockFileSystem))->getPin(self::TEST_PIN_NUMBER);
    }

    public function testExport()
    {
        $exportFile = '/sys/class/gpio/export';

        $mockFileSystem = new FileSystemMock($exportFile);
        $pin = (new PinFactory($mockFileSystem))->getPin(self::TEST_PIN_NUMBER);
        $pin->export();

        $exportStream = $mockFileSystem->open($exportFile, 'r');
        $this->assertEquals(self::TEST_PIN_NUMBER, $exportStream->read(1024));
    }

    public function testUnexport()
    {
        $unexportFile = '/sys/class/gpio/unexport';

        $mockFileSystem = new FileSystemMock($unexportFile);
        $pin = (new PinFactory($mockFileSystem))->getPin(self::TEST_PIN_NUMBER);
        $pin->unexport();

        $unexportStream = $mockFileSystem->open($unexportFile, 'r');
        $this->assertEquals(self::TEST_PIN_NUMBER, $unexportStream->read(1024));
    }
}

class FileSystemMock implements FileSystemInterface
{
    private $expectedFile;

    public function __construct($expectedFile)
    {
        $this->expectedFile = $expectedFile;
    }

    public function getContents($path)
    {
        if ($this->expectedFile !== $path) {
            throw new \InvalidArgumentException('Expected ' . $this->expectedFile . ' got ' . $path);
        }

        return;
    }

    public function putContents($path, $buffer, $flags = 0)
    {
        $this->getContents($path); // proxy

        return;
    }
}
