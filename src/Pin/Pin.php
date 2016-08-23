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
    const GPIO_PIN_FILE_ACTIVE_LOW = 'active_low';

    // Directions
    const DIRECTION_IN = 'in';
    const DIRECTION_OUT = 'out'; // the default output value is LOW
    const DIRECTION_OUT_HIGH = 'high'; // direction out and the default value set to HIGH; useful for active_low logic
    const DIRECTION_OUT_LOW = 'low'; // direction out and the default value set to LOW

    //Active Low values
    const ACTIVE_LOW_TRUE = 1;
    const ACTIVE_LOW_FALSE = 0;

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
        try {
            $this->fileSystem->putContents($directionFile, $direction);
        } catch (\RuntimeException $ex) {
            usleep(100000);
            $this->fileSystem->putContents($directionFile, $direction);
        }
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
     * {@inheritdoc}
     */
    public function setActiveLow($activeLow)
    {
        $activeLowFile = $this->getPinFile(self::GPIO_PIN_FILE_ACTIVE_LOW);
        $this->fileSystem->putContents($activeLowFile, $activeLow ? self::ACTIVE_LOW_TRUE : self::ACTIVE_LOW_FALSE);
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
