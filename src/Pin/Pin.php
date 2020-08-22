<?php

namespace PiPHP\GPIO\Pin;

use PiPHP\GPIO\FileSystem\FileSystemInterface;

abstract class Pin implements PinInterface
{
    // Paths
    public const GPIO_PATH = '/sys/class/gpio/';
    public const GPIO_PREFIX = 'gpio';

    // Files
    public const GPIO_FILE_EXPORT = 'export';
    public const GPIO_FILE_UNEXPORT = 'unexport';

    // Pin files
    public const GPIO_PIN_FILE_DIRECTION = 'direction';
    public const GPIO_PIN_FILE_VALUE = 'value';

    // Directions
    public const DIRECTION_IN = 'in';
    public const DIRECTION_OUT = 'out';
    public const DIRECTION_LOW = 'low';
    public const DIRECTION_HIGH = 'high';

    protected $fileSystem;
    protected $number;

    protected $exported = false;

    /**
     * Constructor.
     *
     * @param FileSystemInterface $fileSystem An object that provides file system access
     * @param int                 $number     The number of the pin
     */
    public function __construct(FileSystemInterface $fileSystem, int $number)
    {
        $this->fileSystem = $fileSystem;
        $this->number = $number;

        $this->export();
    }

    /**
     * {@inheritdoc}
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * {@inheritdoc}
     */
    public function export()
    {
        if (!$this->isExported()) {
            $this->exported = true;

            $this->writePinNumberToFile($this->getFile(self::GPIO_FILE_EXPORT));

            // After export, we need to wait some time for kernel to report changes.
            usleep(200 * 1000);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function unexport()
    {
        if ($this->isExported()) {
            $this->writePinNumberToFile($this->getFile(self::GPIO_FILE_UNEXPORT));
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function isExported(): bool
    {
        $directory = $this->getPinDirectory();

        return $this->fileSystem->exists($directory) && $this->fileSystem->isDir($directory);
    }

    /**
     * {@inheritdoc}
     */
    protected function getDirection(): ?string
    {
        $directionFile = $this->getPinFile(self::GPIO_PIN_FILE_DIRECTION);

        if (!$this->fileSystem->exists($directionFile)) {
            return null;
        }

        return trim($this->fileSystem->getContents($directionFile));
    }

    /**
     * {@inheritdoc}
     */
    protected function setDirection(string $direction)
    {
        if ($this->getDirection() !== $direction) {
            $directionFile = $this->getPinFile(self::GPIO_PIN_FILE_DIRECTION);
            $this->fileSystem->putContents($directionFile, $direction);
            usleep(100 * 1000);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): int
    {
        $valueFile = $this->getPinFile(self::GPIO_PIN_FILE_VALUE);
        return (int) trim($this->fileSystem->getContents($valueFile));
    }

    /**
     * Get the path of the import or export file.
     *
     * @param string $file The type of file (import/export)
     *
     * @return string The file path
     */
    private function getFile(string $file): string
    {
        return self::GPIO_PATH . $file;
    }

    /**
     * Get the path of a pin directory.
     *
     * @return string
     */
    protected function getPinDirectory(): string
    {
        return self::GPIO_PATH . self::GPIO_PREFIX . $this->getNumber();
    }

    /**
     * Get the path of a pin access file.
     *
     * @param string $file The type of pin file (edge/value/direction)
     *
     * @return string
     */
    protected function getPinFile(string $file): string
    {
        return $this->getPinDirectory() . '/' . $file;
    }

    /**
     * Write the pin number to a file.
     *
     * @param string $file The file to write to
     */
    private function writePinNumberToFile(string $file)
    {
        $this->fileSystem->putContents($file, $this->getNumber());
    }
}
