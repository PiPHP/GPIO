<?php

namespace PiPHP\GPIO;

use PiPHP\GPIO\FileSystem\FileSystemInterface;

final class Pin implements PinInterface
{
    // Paths
    const GPIO_PATH      = '/sys/class/gpio/';
    const GPIO_PREFIX    = 'gpio';

    // Files
    const GPIO_FILE_EXPORT   = 'export';
    const GPIO_FILE_UNEXPORT = 'unexport';

    // Pin files
    const GPIO_PIN_FILE_DIRECTION = 'direction';
    const GPIO_PIN_FILE_VALUE     = 'value';
    const GPIO_PIN_FILE_EDGE      = 'edge';

    private $fileSystem;
    private $number;

    public function __construct(FileSystemInterface $fileSystem, $number)
    {
        $this->fileSystem = $fileSystem;
        $this->number = $number;
    }

    public function getNumber()
    {
        return $this->number;
    }

    public function export()
    {
        $this->writePinNumberToFile($this->getFile(self::GPIO_FILE_EXPORT));
    }

    public function unexport()
    {
        $this->writePinNumberToFile($this->getFile(self::GPIO_FILE_UNEXPORT));
    }

    public function getDirection()
    {
        $directionFile = $this->getPinFile(self::GPIO_PIN_FILE_DIRECTION);
        return $this->readFromFile($directionFile);
    }

    public function setDirection($direction)
    {
        $directionFile = $this->getPinFile(self::GPIO_PIN_FILE_DIRECTION);
        $this->writeToFile($directionFile, $direction);
    }

    public function getValue()
    {
        $valueFile = $this->getPinFile(self::GPIO_PIN_FILE_VALUE);
        return $this->readFromFile($valueFile);
    }

    public function setValue($value)
    {
        $valueFile = $this->getPinFile(self::GPIO_PIN_FILE_VALUE);
        $this->writeToFile($valueFile, $value);
    }

    public function getEdge()
    {
        $edgeFile = $this->getPinFile(self::GPIO_PIN_FILE_EDGE);
        return $this->readFromFile($edgeFile);
    }

    public function setEdge($edge)
    {
        $edgeFile = $this->getPinFile(self::GPIO_PIN_FILE_EDGE);
        $this->writeToFile($edgeFile, $edge);
    }

    private function getFile($file)
    {
        return self::GPIO_PATH . $file;
    }

    private function getPinFile($file)
    {
        return self::GPIO_PATH . self::GPIO_PREFIX . $this->getNumber() . '/' . $file;
    }

    private function writePinNumberToFile($file)
    {
        $this->writeToFile($file, $this->getNumber());
    }

    private function writeToFile($file, $value)
    {
        $stream = $this->fileSystem->open($file, "w");
        $stream->write($value);
        $stream->close();
    }

    private function readFromFile($file)
    {
        $stream = $this->fileSystem->open($file, "r");
        $result = $stream->read(1024);
        $stream->close();

        return $result;
    }
}
