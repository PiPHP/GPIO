<?php

namespace PiPHP\GPIO\Pin;

use PiPHP\GPIO\FileSystem\FileSystemInterface;

abstract class Pin implements PinInterface
{
    // Paths
    const GPIO_PATH = '/sys/class/gpio/';
    const GPIO_PREFIX = 'gpio';

    // Files
    const GPIO_FILE_EXPORT = 'export';
    const GPIO_FILE_UNEXPORT = 'unexport';

    // Pin files
    const GPIO_PIN_FILE_DIRECTION = 'direction';
    const GPIO_PIN_FILE_VALUE = 'value';

    // Directions
    const DIRECTION_IN = 'in';
    const DIRECTION_OUT = 'out';

    protected $fileSystem;
    protected $number;

    /**
     * Constructor.
     * 
     * @param FileSystemInterface $fileSystem An object that provides file system access
     * @param int                 $number     The number of the pin
     */
    public function __construct(FileSystemInterface $fileSystem, $number)
    {
        $this->fileSystem = $fileSystem;
        $this->number = $number;

        $this->export();
    }

    /**
     * {@inheritdoc}
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        $this->writePinNumberToFile($this->getFile(self::GPIO_FILE_EXPORT));
    }

    /**
     * {@inheritdoc}
     */
    public function unexport()
    {
        $this->writePinNumberToFile($this->getFile(self::GPIO_FILE_UNEXPORT));
    }

    /**
     * {@inheritdoc}
     */
    protected function setDirection($direction)
    {
        $directionFile = $this->getPinFile(self::GPIO_PIN_FILE_DIRECTION);
        $this->fileSystem->putContents($directionFile, $direction);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue()
    {
        $valueFile = $this->getPinFile(self::GPIO_PIN_FILE_VALUE);
        return (int) $this->fileSystem->getContents($valueFile);
    }

    /**
     * Get the path of the import or export file.
     * 
     * @param string $file The type of file (import/export)
     * 
     * @return string The file path
     */
    private function getFile($file)
    {
        return self::GPIO_PATH . $file;
    }

    /**
     * Get the path of a pin access file.
     * 
     * @param string $file The type of pin file (edge/value/direction)
     * 
     * @return string
     */
    protected function getPinFile($file)
    {
        return self::GPIO_PATH . self::GPIO_PREFIX . $this->getNumber() . '/' . $file;
    }

    /**
     * Write the pin number to a file.
     * 
     * @param string $file The file to write to
     */
    private function writePinNumberToFile($file)
    {
        $this->fileSystem->putContents($file, $this->getNumber());
    }
}
